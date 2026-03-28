
import java.io.Serializable;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Loredana
 */
public class Carte implements Serializable {

    private String denumire, autor;
    private int nrpag;
    private double pret;

    public Carte() {
        denumire = "Harap alb";
        autor = "Ion Creanga";
        nrpag = 25;
        pret = 10;
    }

    public Carte(String d, String a, int n, double p) {
        denumire = d;
        autor = a;
        nrpag = n;
        pret = p;

    }

    public Carte(Carte c) {
        denumire = c.denumire;
        autor = c.autor;
        nrpag = c.nrpag;
        pret = c.pret;
    }

    public String getNume() {
        return denumire;
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

    public void setNume(String d) {
        denumire = d;

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

    public boolean identica(Carte c) {
        boolean identic = true;
        if (!this.denumire.equals(c.denumire)) {
            identic = false;
        } else if (!this.autor.equals(c.autor)) {
            identic = false;
        } else if (nrpag != c.nrpag) {
            identic = false;
        } else if (pret != c.pret) {
            identic = false;
        }
        return identic;
    }

    public String afisare() {
        String s = "";
        s = denumire + " " + "de " + autor + " are " + nrpag + " de pagini si pretul " + pret + " lei";
        // System.out.println(s);
        return s;
    }
}
