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
        PISICA p1=new PISICA();
        p1.afisare();
        PISICA p2=new PISICA("Felina",25,"carnivor","pe pamant","maro","rosu");
        p2.afisare();
        PISICA p3=new PISICA(p2);
        p3.afisare();
        p3.setCuloareOchi("verde");
        p3.setCuloareBlana("portocaliu");
        p3.setGreutate(28.8);
        p3.afisare();
        
        PISICA p4=new PISICA("albastru","roz");
        p4.afisare();
        // TODO code application logic here
    }
}
