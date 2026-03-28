/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public class CALCULATOR {

    private PROCESOR procesor;
    private String tip;

    public CALCULATOR() {
        this.procesor = new PROCESOR();
        this.tip = "";
    }

    public CALCULATOR(PROCESOR procesor, String tip) {
        this.procesor = new PROCESOR(procesor);
        this.tip = tip;
    }

    public CALCULATOR(CALCULATOR calc) {
        this.procesor = new PROCESOR(calc.procesor);
        this.tip = calc.tip;
    }

    public PROCESOR getProcesor() {
        return procesor;
    }

    public String getTip() {
        return tip;
    }

    public void setProcesor(PROCESOR procesor) {
        this.procesor = procesor;
    }

    public void setTip(String tip) {
        this.tip = tip;
    }

    public void afisare() {
        System.out.println("-----------------------------");
        procesor.afisare();
        System.out.println("Calculatorul este de tip: " + tip);

    }

}
