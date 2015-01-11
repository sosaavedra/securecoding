<div class="jumbotron">
	<div class="container">
<table>
	 <tbody>
            <?php foreach ($transactions as $transaction) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction->firstName, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($transaction->lastName, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($transaction->email, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php } ?>
     </tbody>
</table>
</div>