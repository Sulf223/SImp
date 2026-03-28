/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public class PROCESOR {

    private String producator;
    private float frecventa; //GHz
    private int nr_biti_operare;
    private boolean grafica_integrata;

    public PROCESOR() {
        this.producator = "";
        this.frecventa = 0;
        this.nr_biti_operare = 0;
        this.grafica_integrata = Boolean.FALSE;
    }

    public PROCESOR(String producator, float frecventa, int nr_biti_operare, boolean grafica_integrata) {
        this.producator = producator;
        this.frecventa = frecventa;
        this.nr_biti_operare = nr_biti_operare;
        this.grafica_integrata = grafica_integrata;
    }

    public PROCESOR(PROCESOR p) {
        this.producator = p.producator;
        this.frecventa = p.frecventa;
        this.nr_biti_operare = p.nr_biti_operare;
        this.grafica_integrata = p.grafica_integrata;
    }

    public String getProducator() {
        return producator;
    }

    public float getFrecventa() {
        return frecventa;
    }

    public int getNr_biti_operare() {
        return nr_biti_operare;
    }

    public boolean isGrafica_integrata() {
        return grafica_integrata;
    }

    public void setProducator(String producator) {
        this.producator = producator;
    }

    public void setFrecventa(float frecventa) {
        this.frecventa = frecventa;
    }

    public void setNr_biti_operare(int nr_biti_operare) {
        this.nr_biti_operare = nr_biti_operare;
    }

    public void setGrafica_integrata(boolean grafica_integrata) {
        this.grafica_integrata = grafica_integrata;
    }

    public void afisare() {
        System.out.print("PRODUCATOR:" + producator + " FRECVENTA:" + frecventa + " GHz" + " OPEREAZA PE: " + nr_biti_operare + " biti");
        if (this.grafica_integrata) {
            System.out.println(" GRAFICA INTEGRATA:Da");
        } else {
            System.out.println(" GRAFICA INTEGRATA:Nu");
        }
    }

}
