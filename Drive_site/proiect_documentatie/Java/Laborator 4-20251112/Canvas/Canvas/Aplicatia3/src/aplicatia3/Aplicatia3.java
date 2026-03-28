/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia3;

import java.awt.BorderLayout;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Component;
import java.awt.Event;
import java.awt.Graphics;
import java.awt.List;
import java.awt.Panel;
import javax.swing.JFrame;

/**
 *
 * @author Lore
 */
public class Aplicatia3 {

    final int max = 20;
    int x1[] = new int[max];
    int y1[] = new int[max];
    int crt = 0;
    List lista = new List(4, false);
    AplicatiaCanvas canvas = new AplicatiaCanvas();

    public static void main(String[] args) {
        Aplicatia3 aplicatia3 = new Aplicatia3();
    }

    public Aplicatia3() {
        Panel p = new Panel();
        p.setLayout(new BorderLayout());
        p.add("Center", canvas);
        lista.add("patrat");
        lista.add("dreptunghi");
        lista.add("cerc");
        lista.add("elipsa");
        lista.select(0);
        p.add("North", lista);
        JFrame f = new JFrame();
        f.setSize(700, 500);
        f.setTitle("Aplicatia Canvas");
        f.add(p);
        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //centrarea ferestrei
        f.setLocationRelativeTo(null);
        f.setVisible(true);

    }

    public class AplicatiaCanvas extends Canvas {

        @Override
        public boolean mouseDown(Event evt, int x, int y) {
            if (crt < max) {
                adaug(x, y);
                return true;
            } else {
                System.out.println("Prea multe figuri.");
                return false;
            }
        }

        void adaug(int x, int y) {
            x1[crt] = x;
            y1[crt] = y;
            crt++;
            repaint();
        }

        @Override
        public void paint(Graphics g) {
            g.setColor(Color.red);
            for (int i = 0; i < crt; i++) {
                if (lista.isIndexSelected(0)) {
                    g.fillRect(x1[i] - 20, y1[i] - 20, 40, 40);
                } else if (lista.isIndexSelected(1)) {
                    g.fillRect(x1[i] - 25, y1[i] - 10, 50, 20);
                } else if (lista.isIndexSelected(2)) {
                    g.fillOval(x1[i] - 20, y1[i] - 20, 40, 40);
                } else if (lista.isIndexSelected(3)) {
                    g.fillOval(x1[i] - 25, y1[i] - 10, 50, 20);
                }
            }

        }
    }
}
