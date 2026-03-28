/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
import java.awt.*;
import javax.swing.JFrame;
import javax.swing.JPanel;
//2 fire de executie rulate concurent

public class Fir1 extends JPanel implements Runnable {

    Thread executabil, executabil2;
    int x1 = 0, //x1,y1 - pentru desenarea cercurilor din stanga liniei, incep din coltul stanga-sus
            y1 = 0,
            x2 = 800, //x2,y2 - pentru desenarea cercurilor din dreapta liniei, incep din coltul dreapta-jos
            y2 = 400,
            x3 = 380, //x3,y3 - pentru desenarea cercurilor de pe linie
            y3 = 0;

    public static void main(String[] args) {
        Fir1 a = new Fir1();
        a.setSize(800, 450);
        JFrame jf = new JFrame();
        jf.setSize(800, 450);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.setLocationRelativeTo(null);
        jf.add(a);
        jf.setVisible(true);
        jf.setBackground(Color.yellow);
    }

    @Override
    public void addNotify() {
        super.addNotify();

        if (executabil == null) {
            executabil = new Thread(this);
            executabil.start();
        }
        if (executabil2 == null) {
            executabil2 = new Thread(this);
            executabil2.start();
        }
    }

    public void stop() {
        executabil = null;
        executabil2 = null;
    }

    @Override
    public void run() {

        for (int i = 0; i < 250; i++) {
            int width = getWidth(); //obtinerea dimensiunilor componentei
            int height = getHeight();
            if (executabil != null) {
                pauza(50);
                repaint();
                x1 = x1 + 50;
                if (x1 > (330)) {
                    x1 = 0;   //s-a scazut 70 din 400 deoarece cercul are diam 50, iar cercul de pe
                    y1 = y1 + 50;//linie, are diam 40 (20 in stanga liniei)
                } 

                x2 = x2 - 50;
                if (x2 < (420)) {//s-a adunat 20 pt ca se deseneaza din dreapta, sa nu se
                    x2 = width; //suprapuna cu cercurile de pe linie si sa fie simetrie fata de acestea
                    y2 = y2 - 50;
                }

            }
            if (executabil2 != null) {
                pauza(50);
                repaint();
                y3 = y3 + 20;
                if (y3 > height) {
                    y3 = 0;
                }

            }
        }

    }

    public void pauza(int time) {
        try {
            Thread.sleep(time);
        } catch (InterruptedException e) {
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        g.setColor(Color.green);
        int height = getHeight();
        g.drawLine(400, 0, 400, height);
        if (executabil != null) {//se scrie tot in paint ca sa se deseneze de la inceput (0,0)
            g.setColor(Color.red);
            g.fillOval(x1, y1, 50, 50);
            g.setColor(Color.blue);
            g.fillOval(x2, y2, 50, 50);
        }
        if (executabil2 != null) {
            g.setColor(Color.black);
            g.drawOval(x3, y3, 40, 40);
        }
    }

    @Override
    public void update(Graphics g) {
        paint(g);//se suprascrie paint ca sa fie vizibile si figurile desenate anterior, nu doar cea curenta
    }
}
