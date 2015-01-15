package com.securecoding.team17;

import java.awt.Toolkit;
import java.awt.datatransfer.Clipboard;
import java.awt.datatransfer.StringSelection;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import org.apache.commons.lang3.StringUtils;

/**
 * 
 * This class defines the action of copy TAN button, this will simply copy the
 * TAN to clipboard for user convenience
 * 
 * @author Team 17
 *
 */
public class SCSCopyTAN implements ActionListener {

	/**
	 * This method copies TAN to clipboard
	 */
	private void copyTAN() {

		String tanCode = SCSPrepareUI.txtTANCode.getText();
		StringSelection stringSelection = new StringSelection(tanCode);
		Clipboard clpbrd = Toolkit.getDefaultToolkit().getSystemClipboard();
		clpbrd.setContents(stringSelection, null);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * java.awt.event.ActionListener#actionPerformed(java.awt.event.ActionEvent)
	 * This method is invoked on click of copy TAN button
	 */
	@Override
	public void actionPerformed(ActionEvent e) {
		if (StringUtils.isNotBlank(SCSPrepareUI.txtTANCode.getText())) {
			copyTAN();
			SCSPrepareUI.lblTANInfo.setText("TAN copied");
		}
	}
}
