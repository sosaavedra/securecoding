package com.securecoding.team17;

import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

/**
 * 
 * This class defines the action of generate TAN button, this will also do input
 * validations, and have the logic for generating transaction number(15 digits
 * alphanumeric)
 * 
 * @author Team 17
 *
 */
public class SCSGenerateTAN implements ActionListener {

	/**
	 * This method returns 15 digit alphanumeric code, we are creating md5 hash
	 * of PIN, destination account number, amount and salt.
	 * 
	 * @return tanCode
	 */
	private String generateTAN() {

		String tanCode = null;
		String accountNumber = SCSPrepareUI.txtAccountNumber.getText();
		String pin = SCSPrepareUI.txtPIN.getText();
		String amount = SCSPrepareUI.txtAmount.getText();
		String salt = "secureCodingTeam17";

		String inputData = accountNumber + pin + amount + salt;

		tanCode = md5(inputData);

		if (tanCode != null && tanCode.length() > 15) {
			tanCode = tanCode.substring(0, 15);
		}

		return tanCode;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * java.awt.event.ActionListener#actionPerformed(java.awt.event.ActionEvent)
	 * This method is invoked on click of generate TAN button, here we validate
	 * data and if valid, generate TAN code.
	 */
	@Override
	public void actionPerformed(ActionEvent e) {
		// validate input
		if (SCSInputVerifier.verifyInput()) {
			// input valid, generate code and display
			generateTAN();
			SCSPrepareUI.lblTANCode.setVisible(true);
			SCSPrepareUI.txtTANCode.setVisible(true);
			SCSPrepareUI.txtTANCode.setText(generateTAN());
			SCSPrepareUI.txtTANCode.setForeground(Color.BLUE);
			SCSPrepareUI.btnCopyTAN.setVisible(true);
			SCSPrepareUI.lblTANInfo.setVisible(true);
			SCSPrepareUI.lblTANInfo.setText("Copy the above TAN on web transfer page");
			SCSPrepareUI.lblTANInfo.setForeground(Color.BLACK);
			
			SCSPrepareUI.txtPIN.setForeground(Color.BLACK);
			SCSPrepareUI.txtAccountNumber.setForeground(Color.BLACK);
			SCSPrepareUI.txtAmount.setForeground(Color.BLACK);
		} else {
			// input not valid, show error message
			SCSPrepareUI.lblTANCode.setVisible(false);
			SCSPrepareUI.txtTANCode.setVisible(false);
			SCSPrepareUI.btnCopyTAN.setVisible(false);
			SCSPrepareUI.lblTANInfo.setVisible(true);
			SCSPrepareUI.lblTANInfo.setText(SCSInputVerifier.errorMessage);
			SCSPrepareUI.lblTANInfo.setForeground(Color.RED);

			// bring focus for user convenience
			if (SCSInputVerifier.errorCode == 1) {
				// pin code wrong
				SCSPrepareUI.txtPIN.requestFocus();
				SCSPrepareUI.txtPIN.setForeground(Color.RED);
				SCSPrepareUI.txtAccountNumber.setForeground(Color.BLACK);
				SCSPrepareUI.txtAmount.setForeground(Color.BLACK);
			} else if (SCSInputVerifier.errorCode == 2) {
				// account wrong
				SCSPrepareUI.txtAccountNumber.requestFocus();
				SCSPrepareUI.txtAccountNumber.setForeground(Color.RED);
				SCSPrepareUI.txtPIN.setForeground(Color.BLACK);
				SCSPrepareUI.txtAmount.setForeground(Color.BLACK);
			} else if (SCSInputVerifier.errorCode == 3) {
				// amount wrong
				SCSPrepareUI.txtAmount.requestFocus();
				SCSPrepareUI.txtAmount.setForeground(Color.RED);
				SCSPrepareUI.txtPIN.setForeground(Color.BLACK);
				SCSPrepareUI.txtAccountNumber.setForeground(Color.BLACK);
			}
		}
	}

	/**
	 * This method returns alphanumeric md5 hash of given input.
	 * 
	 * @param input
	 * @return md5 hash
	 */
	public static String md5(String input) {

		String md5 = null;

		try {

			// Create MessageDigest object for MD5
			MessageDigest digest = MessageDigest.getInstance("MD5");

			// Update input string in message digest
			digest.update(input.getBytes(), 0, input.length());

			// Converts message digest value in base 16 (hex)
			md5 = new BigInteger(1, digest.digest()).toString(16);

		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace(); // should not happen
		}

		return md5;
	}

}
