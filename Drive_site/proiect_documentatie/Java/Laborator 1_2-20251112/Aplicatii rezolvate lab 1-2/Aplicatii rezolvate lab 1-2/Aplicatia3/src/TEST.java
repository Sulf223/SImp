/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public class TEST {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        PROCESOR p1=new PROCESOR();
        p1.afisare();
        p1.setFrecventa(3.2F);
        p1.afisare();
        PROCESOR p2=new PROCESOR("Intel ",3.2F, 64,Boolean.TRUE);
        p2.afisare();
        
        CALCULATOR c1=new CALCULATOR();
        c1.afisare();
        CALCULATOR c2=new CALCULATOR(p2,"Desktop");
        c2.afisare();
        c2.setTip("Laptop");
        c2.getProcesor().setGrafica_integrata(Boolean.FALSE);
        c2.afisare();
        System.out.println("Procesorul initial este:");
        p2.afisare();
        
    }
}
