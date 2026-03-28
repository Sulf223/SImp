/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package mostenire;

/**
 *
 * @author Lore
 */
public class FILTRU {

    protected String producator;
    protected int capacitate_acvariu;  // maxim litri
    protected int bucati;

    public FILTRU() {
        this.producator = "AQUAEL";
        this.capacitate_acvariu = 0;
        this.bucati = 0;
    }

    public FILTRU(String producator, int capacitate_acvariu, int bucati) {
        this.producator = producator;
        this.capacitate_acvariu = capacitate_acvariu;
        this.bucati = bucati;
    }

    public FILTRU(FILTRU f) {
        this.producator = f.producator;
        this.capacitate_acvariu = f.capacitate_acvariu;
        this.bucati = f.capacitate_acvariu;
    }

    public String getProducator() {
        return producator;
    }

    public int getCapacitate_acvariu() {
        return capacitate_acvariu;
    }

    public int getBucati() {
        return bucati;
    }

    public void setProducator(String producator) {
        this.producator = producator;
    }

    public void setCapacitate_acvariu(int capacitate_acvariu) {
        this.capacitate_acvariu = capacitate_acvariu;
    }

    public void setBucati(int bucati) {
        this.bucati = bucati;
    }


    public void afisare() {
        System.out.println("Producator:" + producator);
        System.out.println("Capacitate acvariu:" + this.capacitate_acvariu+" litri");
        System.out.println("Numar bucati filtru:" + this.bucati);
        
    }

}
