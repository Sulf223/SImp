
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

    private int x = 17, PasX = 7;
    private int y = 12, PasY = 2;
    private int diametru = 20;
    private int xStangaDreptunghi = 10, xDreaptaDreptunghi = 210;
    private int ySusDreptunghi = 10, yJosDreptunghi = 210;
    private boolean ok = true;

    public static void main(String[] args) {
        Minge a = new Minge();
        a.setSize(250, 270);
        JFrame jf = new JFrame();
        jf.setSize(250, 270);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(a);
        jf.setLocationRelativeTo(null);
        jf.setVisible(true);
    }

    @Override
    public void paintComponent(Graphics g) {
        try {
            Thread.sleep(50);
        } catch (InterruptedException e) {
        }
        repaint();
        g.drawRect(xStangaDreptunghi, ySusDreptunghi, xDreaptaDreptunghi, yJosDreptunghi);

        /*desenam cu culoarea fundalului pentru 
		a acoperi mingea anterior desenata*/
        g.setColor(getBackground());

        //se sterge mingea
        g.fillOval(x, y, diametru, diametru);

        //daca se intalneste peretele, se schimba directia de miscare
        //testez daca mingea atinge peretele din stanga; in acest caz schimb sensul de deplasare a mingii
        if (x + PasX <= xStangaDreptunghi-5) {
            PasX = -PasX;
        }
        //testez daca mingea atinge peretele din dreapta
        if (x + PasX >= xDreaptaDreptunghi-5) {
            PasX = -PasX;
        }
        //testez daca mingea atinge peretele de sus
        if (y + PasY <= ySusDreptunghi-5) {
            PasY = -PasY;
        }

        //testez daca mingea atinge peretele de jos
        if (y + PasY >= yJosDreptunghi-7) {
            PasY = -PasY;
        }

        //se modifica noua pozitie a mingii
        x = x + PasX;
        y = y + PasY;

        //se stabileste culoarea rosie pentru desenare
        g.setColor(Color.red);

        //se deseneaza mingea
        g.fillOval(x, y, diametru, diametru);

    }

    /*se apleleaza la distrugerea appletului
	 si are ca efect terminarea metodei paint ()*/
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
