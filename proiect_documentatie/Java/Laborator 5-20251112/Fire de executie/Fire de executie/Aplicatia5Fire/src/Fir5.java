/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
import java.awt.*;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import javax.swing.JFrame;
import javax.swing.JPanel;

public class Fir5 extends JPanel implements Runnable, MouseListener {

    Thread executabil, executabil2;
    int x1, y1, x2, y2, dim1, dim2, crt = 0; //x1,y1, dim1 - informatiile patratelor, x2,y2, dim2 - informatiile cercurilor
    int x3, y3, pas; //coordonatele si pasul cercurilor de pe linie

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
        addMouseListener(this);
    }

    public void stop() {
        executabil = null;
        executabil2 = null;
    }

    @Override
    public void mouseEntered(MouseEvent mouseEvent) {
    }

    @Override
    public void mousePressed(MouseEvent mouseEvent) {
    }

    @Override
    public void mouseReleased(MouseEvent mouseEvent) {
    }

    @Override
    public void mouseExited(MouseEvent mouseEvent) {
    }

    @Override
    public void mouseClicked(MouseEvent mouseEvent) {

        if (crt == 0) {
            crt = 1;
            executabil.suspend();
            executabil2.resume();
        } else {
            crt = 0;
            executabil2.suspend();
            executabil.resume();
        }
    }

    @Override
    public void run() {

        for (int i = 0; i < 100; i++) {
            int width = getWidth();
            int height = getHeight();
            if (crt == 0) {
                x1 = (int) (Math.random() * 400 - 120);
                y1 = (int) (Math.random() * height);
                x2 = 420 + (int) (Math.random() * width / 2);
                y2 = (int) (Math.random() * height);
                dim1 = (int) (Math.random() * 100);
                dim2 = (int) (Math.random() * 80);
                pauza(50);
                repaint();
            } else {
                x3 = 380;
                y3 = 0;
                pas = 20;
                while (y3 < height) {
                    repaint();
                    pauza(50);
                    y3 = y3 + pas;
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
        int width = getWidth();
        int height = getHeight();
        g.drawLine(400, 0, 400, height);
        if (crt == 0) {
            g.setColor(Color.red);
            g.drawRect(x1, y1, dim1, dim1);
            g.setColor(Color.blue);
            g.drawOval(x2, y2, dim2, dim2);
        } else {
            g.setColor(Color.black);
            g.drawOval(x3, y3, 40, 40);
        }
    }

    public static void main(String[] args) {
        Fir5 a = new Fir5();
        a.setSize(800, 400);
        JFrame jf = new JFrame();
        jf.setSize(800, 400);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(a);
        jf.setVisible(true);
        jf.setBackground(Color.yellow);
    }
}
