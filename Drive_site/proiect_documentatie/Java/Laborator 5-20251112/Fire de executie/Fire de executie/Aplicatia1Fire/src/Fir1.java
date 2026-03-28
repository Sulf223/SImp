/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
/*Crearea unei clase care sa defineasca fire de executie poate fi facuta prin doua modalitati:
• prin extinderea clasei Thread;
• prin implementarea interfetei Runnable.
 
 *in cazul in care se doreste sa se creeze o clasa care instantiaza fire de executie,dar aceasta are deja o superclasa,
 se implementeaza interfata Runnable, stiind ca in Java nu este permisa mostenirea multipla.
 */
import java.awt.*;
import javax.swing.JFrame;


/*in cazul in care se foloseste interfata Runnable trebuie ca firele de executie sa fie instantiate utilizandu-se 
  *constructorul din clasa Thread care primeste ca si parametru, o instanta a clasei ce implementeaza interfata (variabila),
  *sau se poate utiliza this. Dupa creare, firul de executie poate fi lansat printr-un apel al metodei start.
  
  **/
class Plansa extends Canvas implements Runnable {

    int x, y;

    @Override
    public void run() {
        for (int i = 0; i < 100; i++) {
            int width = getWidth();
            int height = getHeight();
            x = (int) (Math.random() * width);
            y = (int) (Math.random() * height);
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
    public void paint(Graphics g) {
        g.setColor(Color.blue);
        g.drawRect(x, y, 50, 50);
    }
// Supradefinim update pentru ca desenul sa nu se stearga

    @Override
    public void update(Graphics g) {
        g.setColor(Color.red);
        g.drawRect(x, y, 50, 50);
    }
}

public class Fir1 {

    private Plansa p1;
    private Thread ex;

    public Fir1() {
        JFrame f = new JFrame();
        f.setSize(800, 400);
        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        f.setVisible(true);
// Cream un obiect activ de tip Plansa
        p1 = new Plansa();
        p1.setBackground(Color.yellow);
        f.setLocationRelativeTo(null);
        f.add(p1);
    }

    public void pornireFir() {// Pornim un fir de executie , care va actualiza desenul plansei
        ex = new Thread(p1);
        ex.start();
    }

    public static void main(String args[]) {
        Fir1 f = new Fir1();
        f.pornireFir();
    }
}
