package com.securecoding.team17;

import java.awt.Container;
import java.awt.Font;
import java.awt.Insets;

import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JTextField;
import javax.swing.UIManager;
import javax.swing.UnsupportedLookAndFeelException;

/**
 * 
 * This class has code to create various GUI elements
 * 
 * @author Team 17
 *
 */
public class SCSPrepareUI {

	// Declare variables
	static JFrame scsFrame;
	static Container scsContainer;
	static JButton btnGenerateTAN, btnCopyTAN;
	static JLabel lblPIN, lblAccountNumber, lblAmount, lblTANCode, lblDetailsInfo, lblTANInfo, lblBankSysLogo;
	static JTextField txtPIN, txtAccountNumber, txtAmount, txtTANCode;
	static ImageIcon imgLogo;
	static Insets insets;

	/**
	 * This method inserts the GUI elements at appropriate places.
	 * 
	 * Also defines the action for the button.
	 * 
	 */
	public static void prepareUI() {

		// Set Look and Feel
		try {
			UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
		} catch (ClassNotFoundException | InstantiationException | IllegalAccessException | UnsupportedLookAndFeelException e) {
			// do nothing;
		}

		// Create the frame for the SCS
		scsFrame = new JFrame("Banksys - TAN code generator");
		scsFrame.setSize(600, 360);
		scsContainer = scsFrame.getContentPane();
		insets = scsContainer.getInsets();
		scsContainer.setLayout(null);

		// Create the controls which are required
		btnGenerateTAN = new JButton("GenerateTAN");
		btnCopyTAN = new JButton("Copy TAN to clipboard");
		lblPIN = new JLabel("PIN:");
		lblAccountNumber = new JLabel("Destination account:");
		lblAmount = new JLabel("Amount:");
		lblTANCode = new JLabel("TAN code:");
		lblDetailsInfo = new JLabel("Please ensure that all details are correct then press button below");
		lblTANInfo = new JLabel("Please copy this TAN into website");
		lblBankSysLogo = new JLabel("BankSys");

		txtPIN = new JTextField(6);
		txtAccountNumber = new JTextField(15);
		txtAmount = new JTextField(12);
		txtTANCode = new JTextField(18);

		// Adding all the components to panel
		scsContainer.add(lblPIN);
		scsContainer.add(lblAccountNumber);
		scsContainer.add(lblAmount);
		scsContainer.add(lblTANCode);
		scsContainer.add(lblDetailsInfo);
		scsContainer.add(lblTANInfo);
		scsContainer.add(lblBankSysLogo);

		scsContainer.add(txtPIN);
		scsContainer.add(txtAccountNumber);
		scsContainer.add(txtAmount);
		scsContainer.add(txtTANCode);

		scsContainer.add(btnCopyTAN);
		scsContainer.add(btnGenerateTAN);

		// put all components on GUI
		lblPIN.setBounds(insets.left + 20, insets.top + 20, lblPIN.getPreferredSize().width, lblPIN.getPreferredSize().height);
		txtPIN.setBounds(insets.left + 180, insets.top + 20, txtPIN.getPreferredSize().width, txtPIN.getPreferredSize().height);
		txtPIN.setToolTipText("Enter your 6 digit pin");

		lblBankSysLogo.setFont(new Font("Serif", Font.PLAIN, 36));
		lblBankSysLogo.setBounds(insets.left + 360, insets.top + 20, lblBankSysLogo.getPreferredSize().width, lblBankSysLogo.getPreferredSize().height);

		lblAccountNumber.setBounds(insets.left + 20, txtPIN.getY() + txtPIN.getHeight() + 10, lblAccountNumber.getPreferredSize().width, lblAccountNumber.getPreferredSize().height);
		txtAccountNumber.setBounds(insets.left + 180, lblAccountNumber.getY(), txtAccountNumber.getPreferredSize().width, txtAccountNumber.getPreferredSize().height);
		txtAccountNumber.setToolTipText("Enter destination account number");

		lblAmount.setBounds(insets.left + 20, txtAccountNumber.getY() + txtAccountNumber.getHeight() + 10, lblAmount.getPreferredSize().width, lblAmount.getPreferredSize().height);
		txtAmount.setBounds(insets.left + 180, lblAmount.getY(), txtAmount.getPreferredSize().width, txtAmount.getPreferredSize().height);
		txtAmount.setToolTipText("Enter amount to transfer");

		lblDetailsInfo.setBounds(insets.left + 20, txtAmount.getY() + txtAmount.getHeight() + 20, lblDetailsInfo.getPreferredSize().width, lblDetailsInfo.getPreferredSize().height);

		btnGenerateTAN.setBounds(insets.left + 20, lblDetailsInfo.getY() + lblDetailsInfo.getHeight() + 20, btnGenerateTAN.getPreferredSize().width, btnGenerateTAN.getPreferredSize().height);

		lblTANCode.setBounds(insets.left + 20, btnGenerateTAN.getY() + btnGenerateTAN.getHeight() + 20, lblTANCode.getPreferredSize().width, lblTANCode.getPreferredSize().height);
		txtTANCode.setBounds(insets.left + 180, lblTANCode.getY(), txtTANCode.getPreferredSize().width, txtTANCode.getPreferredSize().height);
		txtTANCode.setToolTipText("Copy this code into bank website");

		lblTANCode.setVisible(false);
		txtTANCode.setVisible(false);
		txtTANCode.setEditable(false);

		btnCopyTAN.setBounds(insets.left + 180, txtTANCode.getY() + txtTANCode.getHeight(), txtTANCode.getWidth(), btnCopyTAN.getPreferredSize().height);
		btnCopyTAN.setVisible(false);

		lblTANInfo.setBounds(insets.left + 20, btnCopyTAN.getY() + btnCopyTAN.getHeight() + 20, lblTANInfo.getPreferredSize().width + 100, lblTANInfo.getPreferredSize().height);
		lblTANInfo.setVisible(false);

		// Set frame visible
		scsFrame.setVisible(true);

		// terminate java process on application exit
		scsFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

	}

}
