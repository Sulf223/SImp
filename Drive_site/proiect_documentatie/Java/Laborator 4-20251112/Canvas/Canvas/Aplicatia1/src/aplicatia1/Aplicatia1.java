/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia1;

import java.awt.BorderLayout;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Panel;
import javax.swing.JFrame;

/**
 *
 * @author Lore
 */

/*Clasa Canvas este o clasa generica din care se deriveaza
subclase pentru crearea suprafetelor de desenare.
Constructorul Canvas() creeaza o plansa, adica o componenta pe
care se poate desena.
Plansele nu pot contine alte componente grafice, ele fiind utilizate
doar ca suprafete de desenat sau ca fundal pentru animatie.
* */
class AplicatiaCanvas extends Canvas {

    @Override
    public void paint(Graphics g) {
        g.setColor(Color.green);
        g.fillRect(250, 250, 300, 300);
        g.drawRect(550, 200, 150, 50);
        g.setColor(Color.blue);
        g.fillOval(300, 300, 200, 200);
        Font f = new Font("Verdana", Font.BOLD, 16);
        g.setFont(f);
        g.drawString("GEOMETRIE", 280, 50);
        g.draw3DRect(25, 10, 50, 75, true);
        g.draw3DRect(25, 110, 50, 75, false);
        g.fill3DRect(100, 10, 50, 75, true);
        g.fill3DRect(100, 110, 50, 75, false);

    }
}

public class Aplicatia1 extends JFrame {

    public static void main(String[] args) {
        Aplicatia1 apl = new Aplicatia1();
        apl.setSize(1000, 800);
        apl.setTitle("Aplicatia Canvas");
        AplicatiaCanvas canvas = new AplicatiaCanvas();
        apl.add(canvas);
        apl.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //centrarea ferestrei
        apl.setLocationRelativeTo(null);
        apl.setVisible(true);

    }
}
