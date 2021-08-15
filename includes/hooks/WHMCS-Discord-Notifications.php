<?php
	$GLOBALS['discordHookURL'] = "";
	$GLOBALS['whmcsAdminURL'] = "";
	$GLOBALS['DiscordRole'] = "";

	add_hook('CancellationRequest', 1, function($vars) {
		$DiscordMessage = "" . json_encode([
			'content' => $GLOBALS['DiscordRole'] . ' New Cacellation Requset',
			'embeds' => [
				[
					'title' => $vars['reason'],
					'url' => $GLOBALS['whmcsAdminURL'] . 'cancelrequest.php',
					'description' => '',
					'fields' => [
						[ 'Product ID' => '', 'value' => $vars['relid'], 'inline' => true ],
						[ 'Cancellation Type' => '', 'value' => $vars['type'], 'inline' => true ],
						[ 'User ID' => '', 'value' => $vars['userid'], 'inline' => true ],
					]
				]
			]
		]);
		processNotification($DiscordMessage, $GLOBALS['discordHookURL'] );
	});
	add_hook('TicketUserReply', 1, function($vars) {
		$DiscordMessage = "" . json_encode([
			'content' => $GLOBALS['DiscordGroup'] . ' New Ticket Reply',
			'embeds' => [
				[
					'title' => $vars['subject'],
					'url' => $GLOBALS['whmcsAdminURL'] . 'supporttickets.php?action=view&id=' . $vars['ticketid'],
					'timestamp' => date(DateTime::ISO8601),
					'description' => $vars['message'],
					'fields' => [
						[ 'Priority' => '', 'value' => $vars['priority'], 'inline' => true ],
						[ 'Department' => '', 'value' => $vars['deptname'], 'inline' => true ],
						[ 'Ticket ID' => '', 'value' => $vars['ticketid'], 'inline' => true ],
					]
				]
			]
		]);
		processNotification($DiscordMessage, $GLOBALS['discordHookURL'] );
	});
	add_hook('TicketOpen', 1, function($vars) {
		$DiscordMessage = "" . json_encode([
			'content' => $GLOBALS['DiscordGroup'] . ' New Support Ticket',
			'embeds' => [
				[
					'title' => $vars['subject'],
					'url' => $GLOBALS['whmcsAdminURL'] . 'supporttickets.php?action=view&id=' . $vars['ticketid'],
					'timestamp' => date(DateTime::ISO8601),
					'description' => $vars['message'],
					'fields' => [
						[ 'Priority' => '', 'value' => $vars['priority'], 'inline' => true ],
						[ 'Department' => '', 'value' => $vars['deptname'], 'inline' => true ],
						[ 'Ticket ID' => '', 'value' => $vars['ticketid'], 'inline' => true ],
					]
				]
			]
		]};
		processNotification($DiscordMessage, $GLOBALS['discordHookURL'] );
	});
	add_hook('InvoicePaid', 1, function($vars) {
		$DiscordMessage = "" . json_encode([
			'content' => $GLOBALS['DiscordGroup'] . ' An invoice has been Paid',
			'embeds' => [
				[
					'title' => 'Invoice #' . $vars['invoiceid'],
					'url' => $GLOBALS['whmcsAdminURL'] . 'viewinvoice.php?id=' . $vars['invoiceid'],
					'timestamp' => date(DateTime::ISO8601)
				]
			]
		]);
		processNotification($DiscordMessage, $GLOBALS['discordHookURL'] );
	});
	
	function processNotification($DiscordMessage, $hook) {
		$ch = curl_int($hook);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $DiscordMessage);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json', 'Content-Length: ' . strlen($DiscordMessage) ]);

		curl_exec($ch);
		curl_close($ch);
	}
?>