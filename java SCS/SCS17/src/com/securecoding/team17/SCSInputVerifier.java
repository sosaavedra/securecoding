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
	public static String errorMessage = "";
	public static int errorCode = 0; // 1 = PIN, 2 = account, 3 = amount 

	/*
	^                   # Start of string.
	[0-9]*              # Must have one or more numbers.
	(                   # Begin optional group.
	    \.              # The decimal point, . must be escaped, 
	                    # or it is treated as "any character".
	    [0-9]{1,2}      # One or two numbers.
	)?                  # End group, signify it's optional with ?
	$                   # End of string.
	*/
	public static final String PATTERN = "^[0-9]*(\\.[0-9]{1,2})?$";

	/**
	 * validate user input
	 * 
	 * @return true if OK
	 */
	public static boolean verifyInput() {

		errorMessage = "";

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
			errorCode = 1;
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
		if (accountNumber != null && accountNumber.length() > 7 && accountNumber.length() < 15) {
			if (NumberUtils.isDigits(accountNumber)) {
				isValid = true;
			} else {
				errorMessage = "account number not proper";
				errorCode = 2;
			}
		} else {
			errorMessage = "account number not in range";
			errorCode = 2;
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
		if (amount != null && amount.length() > 0 && amount.length() < 15) {
			if (amount.matches(PATTERN)) {
				isValid = true;
			} else {
				errorMessage = "amount not proper";
				errorCode = 3;
			}
		} else {
			errorMessage = "amount not in range";
			errorCode = 3;
		}
		return isValid;
	}

}