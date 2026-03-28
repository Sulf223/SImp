/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package concurent;

/**
 *
 * @author Lore
 */
public class Test {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        CONCURENT c1 = new CONCURENT();
        c1.afisare();
        int notele[] = {5, 9, 7, 8, 6, 9};
        CONCURENT c2 = new CONCURENT("MICLEA ANDREI", notele);
        c2.afisare();
        CONCURENT c3 = new CONCURENT(c2);
        c3.afisare();
        c2.setNume("PACURAR CLAUDIA");
        int note2[] = {10, 10, 10, 10, 10, 10};
        c2.setNote(note2);
        c2.afisare();
        System.out.print("Notele concurentului " + c3.getNume() + ":");
        for (int i = 0; i < 6; i++) {
            System.out.print(" " + c3.getNota(i));
        }
        System.out.println("");
        System.out.print("Notele concurentului " + c2.getNume() + ":");
        int note[] = c2.getNotele();
        for (int nota : note) {
            System.out.print(" " + nota);
        }
        System.out.println("");
    }
}
