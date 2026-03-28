/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
import java.awt.Color;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.Toolkit;
import javax.swing.JFrame;
import javax.swing.JPanel;

public class Pisica extends JPanel implements Runnable {

    Image imagini[] = new Image[9];
    Image imgCrt; //imaginea curenta
    Thread executabil;
    int x;
    int y = 100;

    public Pisica() {
        String PisiImg[] = {"dreapta1.gif", "dreapta2.gif",
            "stop.gif", "miauna.gif", "scarpina1.gif",
            "scarpina2.gif", "doarme1.gif", "doarme2.gif",
            "trezire.gif"};

        for (int i = 0; i < imagini.length; i++) {
            imagini[i] = Toolkit.getDefaultToolkit().createImage("src/imag/" + PisiImg[i]);
            imgCrt = imagini[i];

        }
    }

    public static void main(String[] args) {
        Pisica a = new Pisica();
        JFrame jf = new JFrame();
        jf.setSize(800, 400);
        jf.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jf.add(a);
        jf.setVisible(true);
    }

    @Override
    public void addNotify() {
        super.addNotify();
        if (executabil == null) {
            executabil = new Thread(this);
            executabil.start();
        }
    }

    public void stop() {
        executabil = null;
    }

    @Override
    public void run() {
        setBackground(Color.yellow);
        // alearga de la stanga ecranului la jumatatea lui
        // Alergare(0, getSize().width / 2);
        Alergare(0, 400);
        // se opreste si ia o pauza
        imgCrt = imagini[2];
        repaint();
        pauza(1000);
        // casca
        imgCrt = imagini[3];
        repaint();
        pauza(1000);
        // se scarpina de 4 ori
        Scarpinare(4);
        // doarme 5 "reprize"
        Dormire(5);
        // se trezeste si alearga mai departe
        imgCrt = imagini[8];
        repaint();
        pauza(500);
        //Alergare(x, getSize().width+10);
        Alergare(x, 800 + 10);

    }

    void Alergare(int start, int end) {
        for (int i = start; i < end; i += 10) {
            x = i;
            // schimba imaginile
            if (imgCrt == imagini[0]) {
                imgCrt = imagini[1];
            } else {
                imgCrt = imagini[0];
            }
            repaint();
            pauza(150);
        }
    }

    void Scarpinare(int numar) {
        for (int i = numar; i > 0; i--) {
            imgCrt = imagini[4];
            repaint();
            pauza(150);
            imgCrt = imagini[5];
            repaint();
            pauza(150);
        }
    }

    void Dormire(int numar) {
        for (int i = numar; i > 0; i--) {
            imgCrt = imagini[6];
            repaint();
            pauza(250);
            imgCrt = imagini[7];
            repaint();
            pauza(250);
        }
    }

    void pauza(int timp) {
        try {
            Thread.sleep(timp);
        } catch (InterruptedException e) {
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        Font f = new Font("Verdana", Font.BOLD, 16);
        g.setFont(f);
        g.drawString("ANIMATIE PISICA", 280, 50);
        g.drawLine(0,130,800,130);
        if (imgCrt != null) {
            g.drawImage(imgCrt, x, y, this);
        }
    }

}
