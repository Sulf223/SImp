/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package mostenire;

/**
 *
 * @author Lore
 */
public class FILTRU_INTERN extends FILTRU {

    private double putere; //ex: 4.2 W
    private float pret;

    public FILTRU_INTERN() {
        super();
        putere = 1;
        pret = 1;
    }

    public FILTRU_INTERN(String producator, int cap_acvariu, int buc, double p, float pret) {
        super(producator, cap_acvariu, buc);
        this.putere = p;
        this.pret = pret;
    }

    public FILTRU_INTERN(FILTRU filtru, double p, float pret) {
        super(filtru);
        this.putere = p;
        this.pret = pret;
    }

    public FILTRU_INTERN(FILTRU_INTERN filtru) {
        super(filtru);
        this.putere = filtru.putere;
        this.pret = filtru.pret;
    }

    public double getPutere() {
        return putere;
    }

    public float getPret() {
        return pret;
    }

    public void setPutere(double putere) {
        this.putere = putere;
    }

    public void setPret(float pret) {
        this.pret = pret;
    }

    public double total() {
        return getBucati() * pret;
    }

    public void afisare() {
        super.afisare();
        System.out.println("Putere filtru:" + String.format("%.2f", putere) + " W");
        System.out.println("Pret pe bucata:" + String.format("%.2f", pret) + " lei");
        System.out.println("TOTAL:" + String.format("%.3f", this.total()) + " lei");

    }

}
