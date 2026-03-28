/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


import java.sql.Connection;
import java.sql.DriverManager;
import javax.swing.JOptionPane;

/**
 *
 * @author Loredana
 */
public class ConexiuneBD {
    public static Connection conect()
    {
        Connection con=null;
        try{
            Class.forName("com.mysql.jdbc.Driver");
            con=DriverManager.getConnection("jdbc:mysql://localhost:3306/db_librarie?","root","");
        //JOptionPane.showMessageDialog(null, "Conexiune cu succes");
        }
        catch(Exception e)
        {
            System.out.println("Eroare de conectare");
        }
        return con;
    }
}
