/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
import java.awt.*;
import java.awt.event.*;
import javax.swing.JFrame;

class Plansa extends Canvas implements Runnable {
// Deoarece Plansa extinde Canvas, nu mai putem extinde clasa Thread

    Dimension dim = new Dimension(600, 300); //dimensiunea fiecarei suprafete de tip Canvas
    Color culoare;
    String figura;
    int x = 0, y = 0, dim1 = 0, dim2 = 0, x2 = 0, y2 = 0;

    public Plansa(String figura, Color culoare) {
        this.figura = figura;
        this.culoare = culoare;
    }

    @Override
    public Dimension getPreferredSize() { //obtinem dimensiunea dorita a datei membre dim din clasa
        return dim;
    }

    @Override
    public void paint(Graphics g) {
// Desenam un chenar pt fiecare componenta de tip Canvas
        g.setColor(Color.black);
        g.drawRect(0, 0, dim.width - 1, dim.height - 1); //se scade 1 ca sa fie vizibil chenarul, fereastra avand dimensiunea dim
// Desenam figura la coordonatele calculate de firul de executie
        g.setColor(culoare);
        if (figura.equals(" patrat ")) {
            g.drawRect(x, y, dim1, dim1);
        } else if (figura.equals(" cerc ")) {
            g.drawOval(x2, y2, dim2, dim2);
        }
    }

    @Override
    public void update(Graphics g) {
        paint(g);
// Supradefinim update ca sa nu mai fie stearsa suprafata de desenare
    }

    @Override
    public void run() {
        /* Codul firului de executie :
Afisarea a 100 de figuri geometrice la pozitii si dimensiuni calculate aleator.
Intre doua afisari, facem o pauza de 50 ms
         */
        if (figura.equals(" patrat ")) {
            for (int i = 0; i < 100; i++) {

                x = (int) (Math.random() * dim.width);
                y = (int) (Math.random() * dim.height);
                dim1 = (int) (Math.random() * 50);
                pauza(50);
                repaint();
            }
        } else /*Afisarea a 50 de figuri geometrice la pozitii si dimensiuni calculate aleator.
Intre doua afisari, facem o pauza de 50 ms*/ if (figura.equals(" cerc ")) {
            for (int j = 0; j < 50; j++) {

                x2 = (int) (Math.random() * dim.width);
                y2 = (int) (Math.random() * dim.height);
                dim2 = (int) (Math.random() * 100);
                pauza(50);
                repaint();
            }
        }
    }

    public void pauza(int time) {
        try {
            Thread.sleep(time);
        } catch (InterruptedException e) {
        }
    }
}

class Fereastra {

    private Thread ex, ex2;
    private Plansa p1, p2;

    public Fereastra() {

// Instantiem doua obiecte active de tip Plansa
        p1 = new Plansa(" cerc ", Color.red);
        p2 = new Plansa(" patrat ", Color.blue);
        JFrame f = new JFrame();
        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        f.setTitle(" Test Runnable ");
// Acestea extind Canvas, le plasam pe fereastra
        f.setLayout(new BorderLayout());
        f.add("North", p1);
        f.add("South", p2);
        f.pack();
        f.setVisible(true);
    }
    public void pornireFireExecutie()
    {
        // Pornim doua fire de executie , care vor actualiza desenul celor doua planse
        ex = new Thread(p1);
        ex.start();
        ex2 = new Thread(p2);
        ex2.start();
    }
}

public class TestRunnable {

    public static void main(String args[]) {
        Fereastra f = new Fereastra();
        f.pornireFireExecutie();

    }
}
