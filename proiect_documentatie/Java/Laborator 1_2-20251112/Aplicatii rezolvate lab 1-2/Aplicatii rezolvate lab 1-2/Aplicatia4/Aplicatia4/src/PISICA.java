/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Lore
 */
public class PISICA extends MAMIFER{
private String culoare_ochi,culoare_blana;
public PISICA()
{   super();
    culoare_ochi="verde";
    culoare_blana="negru";
}
public PISICA(String co, String cb)
{   super();
    culoare_ochi=co;
    culoare_blana=cb;
}
public PISICA(String r, double g, String t, String l, String co, String cb)
{   super(r,g,t,l);
    culoare_ochi=co;
    culoare_blana=cb;
}

public PISICA(PISICA p)
{super(p.rasa,p.greutate,p.tip,p.loc);
 culoare_ochi=p.culoare_ochi;
 culoare_blana=p.culoare_blana;
}
public String getCuloareOchi()
{
    return culoare_ochi;
}

public String getCuloareBlana()
{
    return culoare_blana;
}
public void setCuloareOchi(String co)
{
    culoare_ochi=co;
}
public void setCuloareBlana(String cb)
{
    culoare_blana=cb;
}

public void afisare()
{ System.out.println("----------------------------------");
  super.afisare();
  System.out.println("Culoare ochi: "+getCuloareOchi());
  System.out.println("Culoare blana: "+getCuloareBlana());
   

}
}
