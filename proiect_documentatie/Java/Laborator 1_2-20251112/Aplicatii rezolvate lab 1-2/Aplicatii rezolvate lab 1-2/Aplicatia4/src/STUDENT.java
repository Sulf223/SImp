/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public class STUDENT extends PERSOANA {

    private String grupa;

    public STUDENT() {
        super();
        grupa = "919 IInd";
    }

    public STUDENT(String n, int v, String g) {
        super(n, v);
        grupa = g;
    }

    public STUDENT(PERSOANA p, String g) {
        super(p);
        grupa = g;
    }

    public STUDENT(STUDENT s) {
        super(s.nume, s.varsta);
        grupa = s.grupa;
    }

    public String getGrupa() {
        return grupa;
    }

    public void setGrupa(String g) {
        grupa = g;
    }

    public void afisare() {
        super.afisare();
        System.out.println(" grupa " + grupa);
    }

}
