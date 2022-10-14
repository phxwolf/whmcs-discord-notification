<?php
$GLOBALS['discordWebHookURL'] = "https://discord.com/api/webhooks/ID/SECRET";
$GLOBALS['whmcsAdminURL'] = "https://website.com";
$GLOBALS['DiscordGroup'] = "@everyone";

add_hook('PendingOrder', 1, function($vars)	{

    $DiscordMessage = "". json_encode([
     	'content' => $GLOBALS['DiscordGroup'] . ' New Pending Order',
		'embeds' => [
			[
				'title' => 'Order Number #' . $vars['orderid'],
				'url' => $GLOBALS['whmcsAdminURL'] . 'orders.php?action=view&id=' . $vars['orderid'],
				'timestamp' => date(DateTime::ISO8601)
			]
		]
	]);
    processNotification($DiscordMessage, $GLOBALS['discordWebHookURL'] );
	
});

add_hook('TicketUserReply', 1, function($vars)	{
	$DiscordMessage = "". json_encode([
     	'content' => $GLOBALS['DiscordGroup'] . ' New Ticket Reply',
		'embeds' => [
			[
				'title' => $vars['subject'],
				'url' => $GLOBALS['whmcsAdminURL'] . 'supporttickets.php?action=view&id=' . $vars['ticketid'],
				'timestamp' => date(DateTime::ISO8601),
				'description' => $vars['message'],
				'fields' => [
					[
					 'name' => 'Priority',
							'value' => $vars['priority'],
							'inline' => true
					],
					[
						'name' => 'Department',
							'value' => $vars['deptname'],
							'inline' => true
					],
					[
					 'name' => 'Ticket ID',
							'value' => $vars['ticketid'],
							'inline' => true
					]
				]
			]
		]
	]);

    processNotification($DiscordMessage, $GLOBALS['discordWebHookURL'] );
});

add_hook('TicketOpen', 1, function($vars)	{
    $DiscordMessage = "". json_encode([
     	'content' => $GLOBALS['DiscordGroup'] . ' New Support Ticket',
		'embeds' => [
			[
				'title' => $vars['subject'],
				'url' => $GLOBALS['whmcsAdminURL'] . 'supporttickets.php?action=view&id=' . $vars['ticketid'],
				'timestamp' => date(DateTime::ISO8601),
				'description' => $vars['message'],
				'fields' => [
					[
						'name' => 'Priority',
						'value' => $vars['priority'],
						'inline' => true
					],
					[
						'name' => 'Department',
						'value' => $vars['deptname'],
						'inline' => true
					],
					[
						'name' => 'Ticket ID',
						'value' => $vars['ticketid'],
						'inline' => true
					]
				]
			]
		]
	]);
    processNotification($DiscordMessage, $GLOBALS['discordWebHookURL'] );
});

/*add_hook('InvoicePaid', 1, function($vars)	{
    $DiscordMessage = "". json_encode([
		'content' => $GLOBALS['DiscordGroup'] . ' An invoice has been Paid',
		'embeds' => [
			[
				'title' => 'Invoice #' . $vars['invoiceid'],
				'url' => $GLOBALS['whmcsAdminURL'] . 'invoices.php?id=' . $vars['invoiceid'],
				'timestamp' => date(DateTime::ISO8601)
			]
		]
	]);

    processNotification($DiscordMessage, $GLOBALS['discordWebHookURL'] );
});*/


function processNotification($DiscordMessage, $hook)	{
	
$ch = curl_init($hook);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $DiscordMessage);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Content-Length: ' . strlen($DiscordMessage)
]);
curl_exec($ch);
curl_close($ch);
}
