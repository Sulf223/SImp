/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia2;

import java.awt.BorderLayout;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Event;
import java.awt.Graphics;
import javax.swing.JFrame;

/**
 *
 * @author Lore
 */
class AplicatiaCanvas extends Canvas {

    final int MAXPETE = 20;
    int xpete[] = new int[MAXPETE];
    int ypete[] = new int[MAXPETE];
    int pataCrt = 0;

    @Override
    public boolean mouseDown(Event evt, int x, int y) {
        if (pataCrt < MAXPETE) {
            adaugarePata(x, y);
            return true;
        } else {
            System.out.println("Prea multe pete.");
            return false;
        }
    }

    public void adaugarePata(int x, int y) {
        xpete[pataCrt] = x;
        ypete[pataCrt] = y;
        pataCrt++;
        repaint();
    }

    @Override
    public void paint(Graphics g) {
        g.setColor(Color.blue);
        for (int i = 0; i < pataCrt; i++) {
            g.fillOval(xpete[i] - 10, ypete[i] - 15, 20, 30);
        }

    }
}

public class Aplicatia2 extends JFrame {

    public static void main(String[] args) {
        Aplicatia2 apl = new Aplicatia2();
        AplicatiaCanvas canvas = new AplicatiaCanvas();
        apl.setSize(700, 500);
        apl.setTitle("Aplicatia Canvas");
        apl.add( canvas);
        apl.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //centrarea ferestrei
        apl.setLocationRelativeTo(null);
        apl.setVisible(true);
    }

}
