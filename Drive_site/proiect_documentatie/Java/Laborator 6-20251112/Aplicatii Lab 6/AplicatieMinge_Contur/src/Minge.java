
import java.applet.Applet;
import java.awt.Color;
import java.awt.Graphics;
import javax.swing.JFrame;
import javax.swing.JPanel;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Lore
 */
public class Minge extends JPanel implements Runnable {

    private int x = 10, PasX = 10;
    private int y = 10, PasY = 10;
    private int diametru = 20;
    private int xStangaDreptunghi = 0, xDreaptaDreptunghi = 210;
    private int ySusDreptunghi = 0, yJosDreptunghi = 210;
    private boolean ok = true;

    public Minge() {
        //  setSize(260,280);
        JFrame jf = new JFrame();
        jf.setSize(280, 300);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(this);
        jf.setVisible(true);

    }

    public static void main(String[] args) {
        Minge a = new Minge();
    }

    public void paint(Graphics g) {
        try {
            Thread.sleep(50);
        } catch (InterruptedException e) {
        }
        repaint();
        g.drawRect(xStangaDreptunghi + 20, ySusDreptunghi + 20, xDreaptaDreptunghi, yJosDreptunghi);

        /*desenam cu culoarea fundalului pentru 
		a acoperi mingea anterior desenata*/
        g.setColor(getBackground());

        //se sterge mingea
        g.fillOval(x, y, diametru, diametru);

        if (x <= xDreaptaDreptunghi && y == 10) {
            x = x + PasX;
        } else if (y <= yJosDreptunghi && x == 220) {
            y = y + PasY;
        } else if (x >= xStangaDreptunghi + 20 && y == 220) {
            x = x - PasX;

        } else if (x >= xStangaDreptunghi && y <= 220) {

            y = y - PasY;
        }

        //se stabileste culoarea albastra pentru desenare
        g.setColor(Color.blue);

        //se deseneaza mingea
        g.fillOval(x, y, diametru, diametru);
    }

    public void destroy() {
        ok = false;
    }

    public void start() {
        ok = true;
        new Thread(this).start();
    }

    public void stop() {
        ok = false;
    }

    public void run() {
        while (ok) {
            repaint();
            pauza(50);
        }
    }

    public void pauza(int time) {
        try {
            Thread.sleep(time);
        } catch (InterruptedException e) {
        }
    }
}
