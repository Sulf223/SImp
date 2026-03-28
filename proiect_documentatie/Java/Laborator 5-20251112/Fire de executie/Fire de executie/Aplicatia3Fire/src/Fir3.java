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

class Fir3 extends JPanel implements Runnable {

    Thread executabil;
    int x1, y1, x2, y2, dim1, dim2;
    //x1 si y1 coordonatele pt patrat
    //x2 si y2 coordonatele  pt cerc

    @Override
    public void addNotify() {
        super.addNotify();
        executabil = new Thread(this);
        executabil.start();
    }

    public void stop() {
        executabil = null;
    }

    @Override
    public void run() {

        for (int i = 0; i < 50; i++) {
            int width = getWidth();
            int height = getHeight();
            x1 = (int) (Math.random() * 400 - 100);
            y1 = (int) (Math.random() * height);
            x2 = 400 + (int) (Math.random() * width / 2);
            y2 = (int) (Math.random() * height);
            dim1 = (int) (Math.random() * 100);
            dim2 = (int) (Math.random() * 80);
            pauza(50);
            repaint();
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
        g.drawLine(400, 0, 400,  getHeight());
        g.setColor(Color.red);
        g.drawRect(x1, y1, dim1, dim1);
        g.setColor(Color.blue);
        g.drawOval(x2, y2, dim2, dim2);
    }

    public static void main(String[] args) {
        Fir3 a = new Fir3();
        a.setSize(800, 400);
        JFrame jf = new JFrame();
        jf.setSize(800, 400);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(a);
        jf.setVisible(true);
        jf.setBackground(Color.yellow);
    }
}
