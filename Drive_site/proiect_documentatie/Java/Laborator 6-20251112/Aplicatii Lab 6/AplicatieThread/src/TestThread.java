/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
//o singura clasa va fi publica, cea a carui nume e acelasi cu numele fisierului java.
// clasa care este derivata din Thread nu va fi publica
class AfisareNumere extends Thread {

    private int a, b, pas;

    public AfisareNumere(int a, int b, int pas) {
        this.a = a;
        this.b = b;
        this.pas = pas;
    }

    @Override
    public void run() {
        for (int i = a; i <= b; i += pas) {
            System.out.println(i + " ");
        }
    }
}

public class TestThread {

    public static void main(String args[]) {
        AfisareNumere fir1, fir2;
// fir 1 va numara de la 0 la 100 cu pasul 5
        fir1 = new AfisareNumere(0, 100, 5);
// fir 2 va numara de la 100 la 200 cu pasul 10
        fir2 = new AfisareNumere(100, 200, 10);

        fir1.start();
        fir2.start();
// Pornim firele de executie
// Ele vor fi distruse automat la terminarea lor
    }
}
