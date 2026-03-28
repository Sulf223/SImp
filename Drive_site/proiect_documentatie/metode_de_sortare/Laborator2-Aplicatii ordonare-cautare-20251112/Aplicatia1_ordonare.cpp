#include <iostream>
#include <string.h>
using namespace std;
struct student{
        char nume[30], pren[30], grupa[10];
        bool bursa;
        int an_studiu, nr_credite ;
     };
struct student st[800];
struct student stb[800];
int n, m;
void Citire(struct student st[], int &n)
{ int i;
    cin >>n;cin.get();
    for(i=0;i<n;i++)
    {   cout <<"Date student: nume pren grupa an nrcred ";
        cin.get(st[i].nume, 30); cin.get();
        cin.get(st[i].pren, 30); cin.get();
        cin.get(st[i].grupa, 10); cin.get();
        cin>>st[i].an_studiu>>st[i].nr_credite;
        cin.get();
        if(st[i].nr_credite >=30)
             st[i].bursa= true;
         else
            st[i].bursa= false;
    }
}
void Afisare(struct student st[], int n)
{ int i;
   cout <<"Lista de studenti este :"<<endl;
   for(i=0;i<n;i++)
        cout <<i+1<<" : "<<st[i].nume<<" "<<st[i].pren<<" "<<st[i].grupa<<" "<<st[i].nr_credite<<" "<<st[i].bursa<<endl;
}

void OrdonareAlf_Interschimbare(struct student st[], int n)
{
    int i, j;
    ///Metoda interschimbarii
    struct student aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++) /// x[i] >x[j]
          if((strcmp (st[i].nume, st[j].nume)>0 ) || (strcmp (st[i].nume, st[j].nume)==0 && strcmp(st[i].pren, st[j].pren)>0 ))
          {
          aux= st[i];
          st[i]= st[j];
          st[j]=aux;
          }
}
void OrdonareAlf_Selectie(struct student st[], int n)
{
    int i, j, poz;
    ///Metoda selectie
    struct student minx;
     for(i=0;i<n-1;i++)
     {
        minx=st[i]; poz=i;
       for(j=i+1;j<n;j++)
          if((strcmp (minx.nume, st[j].nume)>0 ) || (strcmp (minx.nume, st[j].nume)==0 && strcmp(minx.pren, st[j].pren)>0 ))
          {
           minx= st[j];
           poz=j;
          }

        ///st[i] cu st[poz]
        st[poz]=st[i];
        st[i]= minx;
     }
}

void OrdonareCredite(struct student st[], int n)
{int i, j;
    ///Metoda interschimbarii
    struct student aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
          if(st[i].nr_credite < st[j].nr_credite || (st[i].nr_credite == st[j].nr_credite && strcmp(st[i].nume, st[j].nume)>0 ))
          {
          aux= st[i];
          st[i]= st[j];
          st[j]=aux;
          }
}
void OrdonareCredite1(struct student st[], int n)
{int i, j; bool ok;
    ///Metoda bubble sort
    struct student aux;
    do{
      ok=true;
      for(i=0;i<n-1;i++)
       if(st[i].nr_credite < st[i+1].nr_credite || (st[i].nr_credite == st[i+1].nr_credite && strcmp(st[i].nume, st[i+1].nume)>0 ))
          {
          aux= st[i];
          st[i]= st[i+1];
          st[i+1]=aux;
          ok=false;
          }
    }while(ok==false);
}
void OrdonareInserDirecta(struct student st[], int n, struct student stb[], int &m)
{int i, j;
    ///Metoda Insertiei Directe
    struct student y;
    m=0;
     for(i=0;i<n;i++)
     if(st[i].nr_credite>=30)
      {
        j=m-1;
        y=st[i];
        while ((j>=0) && (stb[j].nr_credite>y.nr_credite))
        {
            stb[j+1]=stb[j];
            j--;
        }
        stb[j+1]=y;
     m++;
     }
}
void OrdonareAlfGrupa(struct student st[], int n)
{int i, j;
    ///Metoda interschimbarii
    struct student aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
          if((strcmp (st[i].grupa, st[j].grupa)>0 ) || (strcmp (st[i].grupa, st[j].grupa)==0 && strcmp(st[i].nume, st[j].nume)>0 ))
          {
          aux= st[i];
          st[i]= st[j];
          st[j]=aux;
          }
}
void CautareSecventiala (struct student st[], int n, char nm[20], char pr[20])
{  int i, poz;
    poz=-1;
  for(i=0;i<n;i++)
        if(strcmp(st[i].nume, nm)==0 &&  strcmp(st[i].pren, pr)==0 )
            poz=i;
  if(poz>-1)
     cout <<st[i].nume<<" "<<st[i].pren<<" "<<st[i].grupa<<" "<<st[i].nr_credite<<" "<<st[i].bursa<<endl;
  else
     cout <<"Nu exista";
  }

  void CautareBinara(struct student st[], int n, char nm[20], char pr[20])
{ /// Doar daca tabloul este ordonat
 int s, d, ok, m;
    s=0; d=n-1;
    ok=0;
    while (s <=d && ok==0)
    {
        m= (s+d)/2;
        /// Verific pe cel din mijloc
        if(strcmp(st[m].nume, nm)==0 &&  strcmp(st[m].pren, pr)==0 )
            ok=1;
    }
    if(ok==1)
        cout <<st[m].nume<<" "<<st[m].pren<<" "<<st[m].grupa<<" "<<st[m].nr_credite<<" "<<st[m].bursa<<endl;
  else
     cout <<"Nu exista";
}
int main()
{ char nm[30], pr[30];
    Citire(st, n);
    Afisare(st, n);
    OrdonareAlf_Interschimbare(st, n);
    cout <<endl;
    Afisare(st, n);
    OrdonareAlf_Selectie(st, n);
    cout <<endl;
    Afisare(st, n);
  //  OrdonareCredite(st, n);
  ///  Afisare(st, n);
 ///   OrdonareCredite1(st, n);
 ///   Afisare(st, n);
 ///   OrdonareAlfGrupa(st, n);
 ///   Afisare(st, n);
    OrdonareInserDirecta(st, n, stb, m);
    Afisare(stb,m);
    cout<<"Dati nume de cautat ";
    cin >>nm;
     cout<<"Dati prenume de cautat ";
    cin >>pr;
    CautareSecventiala(st, n, nm, pr);
    OrdonareAlf_Selectie(st, n);
    CautareBinara(st, n, nm, pr);

    return 0;
}
