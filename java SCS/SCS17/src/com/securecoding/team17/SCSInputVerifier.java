package com.securecoding.team17;

import org.apache.commons.lang3.math.NumberUtils;

/**
 * 
 * This class has logic to validate the user input.
 * 
 * @author Team 17
 *
 */
public class SCSInputVerifier {

	/**
	 * to show the error message to user
	 */
	public static String errorMessage;

	/**
	 * validate user input
	 * 
	 * @return true if OK
	 */
	public static boolean verifyInput() {

		errorMessage = null;

		// check PIN
		boolean isValid = false;

		isValid = checkPIN();

		// if PIN valid, check account number
		if (isValid) {
			isValid = checkAccountNumber();
		}

		// if account number valid, check amount
		if (isValid) {
			isValid = checkAmount();
		}

		return isValid;
	}

	/**
	 * method to validate PIN
	 * 
	 * @return true if OK
	 */
	private static boolean checkPIN() {

		boolean isValid = false;
		// get the PIN
		String pin = SCSPrepareUI.txtPIN.getText();
		// should be 6 digits number
		if (pin != null && pin.length() == 6) {
			isValid = NumberUtils.isDigits(pin);
		} else {
			errorMessage = "PIN invalid";
		}
		return isValid;
	}

	/**
	 * method to validate account number
	 * 
	 * @return true if OK
	 */
	private static boolean checkAccountNumber() {

		boolean isValid = false;
		// get the account number
		String accountNumber = SCSPrepareUI.txtAccountNumber.getText();
		// check for length range and numeric
		if (accountNumber != null && accountNumber.length() > 1 && accountNumber.length() < 20) {
			if (NumberUtils.isDigits(accountNumber)) {
				isValid = true;
			} else {
				errorMessage = "account number not proper";
			}
		} else {
			errorMessage = "account number not in range";
		}
		return isValid;
	}

	/**
	 * method to validate amount
	 * 
	 * @return true if OK
	 */
	private static boolean checkAmount() {

		boolean isValid = false;
		// get the amount
		String amount = SCSPrepareUI.txtAmount.getText();
		// check length and numeric
		if (amount != null && amount.length() > 0 && amount.length() < 20) {
			if (NumberUtils.isDigits(amount)) {
				isValid = true;
			} else {
				errorMessage = "amount not proper";
			}
		} else {
			errorMessage = "amount not in range";
		}
		return isValid;
	}

}