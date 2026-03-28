
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Lore
 */
public class TEST {
//nu se pot instantia obiecte de tipul claselor abstracte, ci doar pt clasele derivate din clasa abstracta

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        BufferedReader tastatura;
        String linie;
        STUDENT s[] = new STUDENT[20];

        int n = 0, i;
        try {
            /* obtinerea unui flux de la intrarea standard	(de la tastatura) */
            tastatura = new BufferedReader(new InputStreamReader(System.in));
            System.out.flush();
            //citirea datelor despre persoane 
            System.out.println("Dati nr de persoane pentru care doriti sa introduceti informatii: ");
            linie = tastatura.readLine();
            n = Integer.valueOf(linie);
            System.out.flush();
            for (i = 0; i < n; i++) {
                // citirea datelor 
                s[i] = new STUDENT();
                System.out.print("Dati numele persoanei " + (i + 1) + ":");
                linie = tastatura.readLine();
                s[i].setNume(linie);
                System.out.print("Dati varsta persoanei " + s[i].getNume() + ":");
                linie = tastatura.readLine();
                s[i].setVarsta(Integer.valueOf(linie));
                System.out.print("Dati grupa studentului " + s[i].getNume() + ":");
                linie = tastatura.readLine();
                s[i].setGrupa(linie);

            }
            System.out.println("Datele introduse sunt: ");
            for (i = 0; i < n; i++) {
                s[i].afisare();
            }
            int max = s[0].getVarsta();
            for (i = 1; i < n; i++) {
                if (s[i].getVarsta() > max) {
                    max = s[i].getVarsta();
                }
            }
            System.out.println("Varsta cea mai mare o au studentii:");
            for (i = 0; i < n; i++) {
                if (max == s[i].getVarsta()) {
                    System.out.println(s[i].getNume());
                }
            }

            tastatura.close(); 	// inchiderea intrarii standard 
        } catch (IOException e) {
            // tratarea cazului de excep?ie 
            System.err.println("Citire gresita de la tastatura: " + e);
        }
        // TODO code application logic here
    }
}
