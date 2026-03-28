/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package aplicatia5;

import java.awt.BorderLayout;
import java.awt.Canvas;
import java.awt.Color;
import java.awt.Event;
import java.awt.Graphics;
import java.awt.Point;
import javax.swing.JFrame;

/**
 *
 * @author Lore
 */
public class Aplicatia5{
final int MAXLINII = 10;
    Point start[] = new Point[MAXLINII]; // puncte de start
    Point stop[] = new Point[MAXLINII];  // puncte de sfarsit
    Point inceput;    // inceput linie curenta
    Point curent; // sfarsit linie curenta
    int linieCrt = 0; // numar linii
    int l,h;
    
    
    AplicatiaCanvas canvas=new AplicatiaCanvas();
    
    public static void main(String[] args) {
       Aplicatia5 apl=new Aplicatia5();
    }
    
     public Aplicatia5()
    {  JFrame f=new JFrame(); 
        f.setSize(700,500);
        f.setTitle("Aplicatia Canvas");
        f.add(canvas);
        
        f.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        //centrarea ferestrei
        f.setLocationRelativeTo(null);
        
        f.setVisible(true);
    }
     
     public class AplicatiaCanvas extends Canvas {
          
          @Override
          public boolean mouseDown(Event evt, int x, int y) {
        if (linieCrt < MAXLINII) {
            inceput = new Point(x,y);
             return true;
        }
        else  {
            System.out.println("Prea multe linii.");
            return false;
        }
    }
          @Override
    public boolean mouseUp(Event evt, int x, int y) {
        if (linieCrt < MAXLINII) {
             adaugareLinie(x,y);
             return true;
        }
        else return false;
    }
          @Override
    public boolean mouseDrag(Event evt, int x, int y) {
        if (linieCrt < MAXLINII) {
            curent = new Point(x,y);
             repaint();
            return true;
        }
        else return false;
    }

    void adaugareLinie(int x,int y) {
        start[linieCrt] = inceput;
        stop[linieCrt] = new Point(x,y);
        linieCrt++;
        curent = null;
        inceput = null;
        repaint();
    }
          @Override
      public void paint(Graphics g){
      for (int i = 0; i < linieCrt; i++) {
          	g.drawLine(start[i].x,start[i].y,stop[i].x,stop[i].y);
          	l=stop[i].x-start[i].x;
          	h=stop[i].y-start[i].y;
          	  if (l<0&&h<0) 	g.drawRect(stop[i].x,stop[i].y,Math.abs(l),Math.abs(h));
           	else if(l<0&&h>0)g.drawRect(stop[i].x,start[i].y,Math.abs(l),h);
           		else if(l>0&&h>0)g.drawRect(start[i].x,start[i].y,l,h);
           				else if(l>0&&h<0) g.drawRect(start[i].x,stop[i].y,l,Math.abs(h));
                     
           
        }
        g.setColor(Color.blue);
        if (curent != null)
           g.drawLine(inceput.x,inceput.y, curent.x,curent.y);
		
	}  
      }
}
