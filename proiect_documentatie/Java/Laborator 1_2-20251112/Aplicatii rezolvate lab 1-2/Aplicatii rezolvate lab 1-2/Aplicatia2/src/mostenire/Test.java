/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package mostenire;

/**
 *
 * @author Lore
 */
public class Test {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        FILTRU f1=new FILTRU("TETRA",30,10);
        f1.afisare();
        FILTRU f2=new FILTRU();
        f2.afisare();
        FILTRU_INTERN fi1=new FILTRU_INTERN("AQUAEL",40,5,4.2,85);
        fi1.afisare();
        FILTRU_INTERN fi2=new FILTRU_INTERN(f1,4.7,120);
        fi2.afisare();
        FILTRU_INTERN fi3=new FILTRU_INTERN();
        fi3.afisare();
        fi3.setBucati(8);
        fi3.setPret(200);
        fi3.afisare();
        
        
        // TODO code application logic here
    }
}
