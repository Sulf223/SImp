/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia4;

import java.awt.BorderLayout;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Event;
import java.awt.Graphics;
import java.awt.Point;
import javax.swing.JFrame;

/**
 *
 * @author Lore
 */
public class Aplicatia4 {

    final int MAXLINII = 10;
    Point start[] = new Point[MAXLINII]; // puncte de start
    Point stop[] = new Point[MAXLINII];  // puncte de sfarsit
    Point inceput;    // inceput linie curenta
    Point curent; // sfarsit linie curenta
    int linieCrt = 0; // numar linii

    AplicatiaCanvas canvas = new AplicatiaCanvas();

    public static void main(String[] args) {
        Aplicatia4 apl = new Aplicatia4();
    }

    public Aplicatia4() {
        JFrame f = new JFrame();
        f.setSize(700, 500);
        f.setTitle("Aplicatia Canvas");
        f.add(canvas);

        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //centrarea ferestrei
        f.setLocationRelativeTo(null);

        f.setVisible(true);
    }

    public class AplicatiaCanvas extends Canvas {

        @Override
        public boolean mouseDown(Event evt, int x, int y) {
            if (linieCrt < MAXLINII) {
                inceput = new Point(x, y);
                return true;
            } else {
                System.out.println("Prea multe linii.");
                return false;
            }
        }

        @Override
        public boolean mouseUp(Event evt, int x, int y) {
            if (linieCrt < MAXLINII) {
                addlinie(x, y);
                return true;
            } else {
                return false;
            }
        }

        @Override
        public boolean mouseDrag(Event evt, int x, int y) {
            if (linieCrt < MAXLINII) {
                curent = new Point(x, y);
                repaint();
                return true;
            } else {
                return false;
            }
        }

        void addlinie(int x, int y) {
            start[linieCrt] = inceput;
            stop[linieCrt] = new Point(x, y);
            linieCrt++;
            repaint();
            inceput = null;
            curent = null;
        }

        @Override
        public void paint(Graphics g) {
            for (int i = 0; i < linieCrt; i++) {
                g.drawLine(start[i].x, start[i].y, stop[i].x, stop[i].y);

            }
            g.setColor(Color.blue);
            if (curent != null) {
                g.drawLine(inceput.x, inceput.y, curent.x, curent.y);
            }

        }
    }
}
