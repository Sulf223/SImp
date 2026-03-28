/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package concurent;

/**
 *
 * @author Lore
 */
public class CONCURENT {

    private String nume;
    private int note[];

    public CONCURENT() {
        nume = "POP MIHAELA";
        note = new int[6];
        for (int i = 0; i < 6; i++) {
            note[i] = 1;
        }
    }

    public CONCURENT(String n, int not[]) {
        nume = n;
        //nume=new String(n);
        note = new int[6];
        for (int i = 0; i < 6; i++) {
            note[i] = not[i];
        }
    }

    public CONCURENT(CONCURENT c) {
        //nume=new String(c.nume);
        nume = c.nume;
        note = new int[6];
        for (int i = 0; i < 6; i++) {
            note[i] = c.note[i];
        }
    }

    public String getNume() {
        return nume;
    }

    public void setNume(String nume) {
        this.nume = nume;
    }

    public int getNota(int i) {
        return note[i];
    }

    public int[] getNotele() {
        return note;
    }

    public void setNote(int not[]) {
        note = new int[6];
        for (int i = 0; i < 6; i++) {
            note[i] = not[i];
        }
    }

    public double punctaj() {
        int i;
        double s = 0;
        for (i = 0; i < 6; i++) {
            s += note[i];
        }
        s = s / 6;
        return s;
    }

    public int minim() {
        int min;
        min = note[0];
        for (int i = 1; i < 6; i++) {
            if (min > note[i]) {
                min = note[i];
            }
        }
        return min;
    }

    public int maxim() {
        int max;
        max = note[0];
        for (int i = 1; i < 6; i++) {
            if (max < note[i]) {
                max = note[i];
            }
        }
        return max;
    }

    public void afisare() {
        System.out.println("Numele este: " + nume + " si are punctajul " + punctaj());
        if (minim() == maxim()) {
            System.out.println("Nota " + maxim() + " este nota cea mai mare si cea mai mica");
        } else {
            System.out.println("Cea mai mica nota este " + minim() + " iar cea mai mare este " + maxim());
        }

    }

}
