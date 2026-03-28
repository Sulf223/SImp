
import java.awt.Color;
import java.awt.Graphics;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
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
public class Aplicatia2Fire extends JPanel implements Runnable, MouseListener {

    Thread executabil;
    int x;
    int y;
    int dimensiune;  //latura patratului
    int crt = 0;

    @Override
    public void addNotify() {
        super.addNotify();
        executabil = new Thread(this);
        executabil.start();
        addMouseListener(this);
    }

    public void stop() {
        executabil = null;
    }
//deoarece clasa implementeaza interfata MouseListener trebuie declarate toate metodele sale...mouseEntered, etc

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
        } else {
            crt = 0;
            executabil.resume();
        }
    }

    @Override
    public void run() {
        for (int i = 0; i < 100; i++) {
            int width = getWidth();
            int height = getHeight();
            x = (int) (Math.random() * width);
            y = (int) (Math.random() * height);
            dimensiune = (int) (Math.random() * 100);
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
        g.setColor(Color.blue);
        g.drawRect(x, y, dimensiune, dimensiune);
    }

    public static void main(String[] args) {
        Aplicatia2Fire a = new Aplicatia2Fire();
        a.setSize(800, 400);
        JFrame jf = new JFrame();
        jf.setSize(800, 400);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(a);
        jf.setVisible(true);
        jf.setBackground(Color.yellow);
    }
}
