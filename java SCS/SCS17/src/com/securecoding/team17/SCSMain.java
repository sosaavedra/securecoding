package com.securecoding.team17;

/**
 * This is main class for smart card simulator
 * 
 * @author Team17
 *
 */
public class SCSMain {

	public static void main(String args[]) {

		// prepare the GUI
		SCSPrepareUI.prepareUI();

		// all logic related to generating TAN is in SCSGenerateTAN
		// generate TAN Button's action
		SCSPrepareUI.btnGenerateTAN.addActionListener(new SCSGenerateTAN()); // Register action
																				

		// copy to clipboard action
		SCSPrepareUI.btnCopyTAN.addActionListener(new SCSCopyTAN());
	}

}
