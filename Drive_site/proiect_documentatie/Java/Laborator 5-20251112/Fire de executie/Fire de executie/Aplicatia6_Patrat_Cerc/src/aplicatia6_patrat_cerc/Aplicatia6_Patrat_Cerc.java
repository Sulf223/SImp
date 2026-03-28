/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia6_patrat_cerc;

import java.awt.Canvas;
import java.awt.Color;
import java.awt.Graphics;
import javax.swing.JFrame;

/**
 *
 * @author Loredana
 */
class Desen extends Canvas implements Runnable {

    Thread executabil;
    int x, y, x0, y0, L;
    //x0 si y0 - coordonatele centrului patratului
    //x si y - coordonatele figurii care se deseneaza cu firul de executie
    
    public Desen() {
        x0 = 150;
        y0 = 150;
        L = 200;
        x = (int) (x0 - L / 2);
        y = (int) (y0 - L / 2);
    }

    public void start() {
        if (executabil == null) {
            executabil = new Thread(this); // sau executabil = new Thread(executabil);
            executabil.start();
        }
    }

    public void stop() {
        executabil = null;
    }

    @Override
    public void run() {
        repaint();
        pauza(100);
        while (x < x0 + L / 2) {
            x += 5;
            repaint();
            pauza(100);
        }
        while (y < y0 + L / 2) {
            y += 5;
            repaint();
            pauza(100);
        }
        while (x > x0 - L / 2) {
            x -= 5;
            repaint();
            pauza(100);
        }
        while (y > y0 - L / 2) {
            y -= 5;
            repaint();
            pauza(100);
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
        g.drawOval(x - 10, y - 10, 20, 20);
        g.setColor(Color.red);
        g.drawRect((int) (x0 - L / 2), (int) (y0 - L / 2), L, L);
    }
// Supradefinim update pentru ca desenul sa nu se stearga

    @Override
    public void update(Graphics g) {
        g.setColor(Color.blue);
        g.drawOval(x - 10, y - 10, 20, 20);
        g.setColor(Color.red);
        g.drawRect((int) (x0 - L / 2), (int) (y0 - L / 2), L, L);

    }
}

public class Aplicatia6_Patrat_Cerc{
    private Desen desen;
    private Thread ex;
 
    public Aplicatia6_Patrat_Cerc() {
        desen = new Desen();
        desen.setSize(800, 400);
        desen.setBackground(Color.yellow);
        JFrame f = new JFrame();
        f.setSize(800, 400);
        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        f.setVisible(true);
        f.setLocationRelativeTo(null);
        f.add(desen);
        f.setTitle("Aplicatia Canvas cu fir de executie");
        f.add("Center", desen);
        f.setVisible(true);
        
    }
    public void pornireFir()
    {   ex = new Thread(desen);
        ex.start();
    }

    public static void main(String[] args) {
        Aplicatia6_Patrat_Cerc a = new Aplicatia6_Patrat_Cerc();
        a.pornireFir(); 
      }
}
