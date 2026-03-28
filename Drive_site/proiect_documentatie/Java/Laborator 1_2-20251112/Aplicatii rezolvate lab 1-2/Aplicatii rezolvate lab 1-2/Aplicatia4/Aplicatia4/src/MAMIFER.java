/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public abstract class MAMIFER { //o clasa declarata abstracta nu poate fi instantiata
    protected String rasa;
protected double greutate;
protected String tip; // ierbivor, carnivor, vertebrat....
protected String loc; //pe pamant, in apa...

public MAMIFER()
{rasa="Panthera";
greutate=50.5; //kg
tip="carnivor";
loc="pe pamant";
}

public MAMIFER(String r, double g, String t, String l)
{
    rasa=r;
    greutate=g;
    tip=t;
    loc=l;
}

public MAMIFER(MAMIFER m)
{
    rasa=m.rasa;
    greutate=m.greutate;
    tip=m.tip;
    loc=m.loc;
}

public String getRasa()
{
    return rasa;
}
public double getGreutate()
{
    return greutate;
}
public String getTip()
{
    return tip;
}
public String getLoc()
{
    return loc;
}
public void setRasa(String r)
{
    rasa=r;
}
public void setGreutate(double greutate)
{
    this.greutate=greutate;
}
public void setTip(String t)
{
    tip=t;
}
public void setLoc(String l)
{
    loc=l;
}

public void afisare()
{
    System.out.println("Rasa: "+getRasa());
    System.out.println("Greutate= "+String.format("%.2f", getGreutate())+" kg");
    System.out.println("Tipul: "+getTip());
    System.out.println("Traieste: "+getLoc());
}
    
}
