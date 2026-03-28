/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public abstract class PERSOANA {//o clasa declarata abstracta nu poate fi instantiata

    protected String nume;
    protected int varsta;

    public PERSOANA() {
        nume = "Popescu Maria";
        varsta = 25;
    }

    public PERSOANA(String n, int v) {
        nume = n;
        varsta = v;
    }

    public PERSOANA(PERSOANA p) {
        nume = p.nume;
        varsta = p.varsta;
    }

    public String getNume() {
        return nume;

    }

    public int getVarsta() {
        return varsta;
    }

    public void setNume(String n) {
        nume = n;
    }

    public void setVarsta(int v) {
        varsta = v;
    }

    public void afisare() {
        System.out.print(nume + " " + varsta + " ani");
    }

}
