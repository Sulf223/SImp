

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Loredana
 */
public class Carte {

    private String titlu, autor;
    private int nrpag;
    private double pret;

    public Carte() {
        titlu = "Harap alb";
        autor = "Ion Creanga";
        nrpag = 25;
        pret = 10;
    }

    public Carte(String d, String a, int n, double p) {
        titlu = d;
        autor = a;
        nrpag = n;
        pret = p;

    }

    public Carte(Carte c) {
        titlu = c.titlu;
        autor = c.autor;
        nrpag = c.nrpag;
        pret = c.pret;
    }

    public String getTitlu() {
        return titlu;
    }

    public String getAutor() {
        return autor;
    }

    public int getNrpag() {
        return nrpag;
    }

    public double getPret() {
        return pret;
    }

    public void setTitlu(String d) {
        titlu = d;

    }

    public void setAutor(String a) {
        autor = a;
    }

    public void setNrpag(int nr) {
        nrpag = nr;
    }

    public void setPret(double p) {
        pret = p;
    }

    public String afisare() {
        String s = "";
        s = titlu + " scrisa de " + autor + " are " + nrpag + " pagini si pretul " + pret;
        return s;
    }
}
