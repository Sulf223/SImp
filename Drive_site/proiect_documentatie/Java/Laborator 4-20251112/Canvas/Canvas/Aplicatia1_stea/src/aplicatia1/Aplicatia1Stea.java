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
        Font f = new Font("Verdana", Font.BOLD, 16);
        g.setFont(f);
        g.drawString("GEOMETRIE", 280, 50);
        g.draw3DRect(25, 10, 50, 75, true);
        g.draw3DRect(25, 110, 50, 75, false);
        g.fill3DRect(100, 10, 50, 75, true);
        g.fill3DRect(100, 110, 50, 75, false);

        int x[] = {397, 372, 291, 356, 332, 397, 461, 436, 500, 421};
        int y[] = {285, 361, 361, 409, 485, 437, 485, 409, 361, 361};
        g.setColor(Color.red);
        g.fillOval(x[0] - 5, y[0] - 5, 10, 10);
        g.setColor(Color.yellow);
        g.fillPolygon(x, y, x.length);
    }
}

public class Aplicatia1Stea extends JFrame {

    public static void main(String[] args) {
        Aplicatia1Stea apl = new Aplicatia1Stea();
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
