#include <iostream>
#include <string.h>
using namespace std;
struct student{
        char nume[30], pren[30], grupa[10], bursa[3];
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
             strcpy(st[i].bursa, "DA");
         else
            strcpy(st[i].bursa, "NU");
    }
}
void Afisare(struct student st[], int n)
{ int i;
   cout <<"Lista de studenti este :"<<endl;
   for(i=0;i<n;i++)
        cout <<i+1<<" : "<<st[i].nume<<" "<<st[i].pren<<" "<<st[i].grupa<<" "<<st[i].nr_credite<<" "<<st[i].bursa<<endl;
}
void OrdonareAlf(struct student st[], int n)
{int i, j;
    ///Metoda interschimbarii
    struct student aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
          if((strcmp (st[i].nume, st[j].nume)>0 ) || (strcmp (st[i].nume, st[j].nume)==0 && strcmp(st[i].pren, st[j].pren)>0 ))
          {
          aux= st[i];
          st[i]= st[j];
          st[j]=aux;
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
    ///Metoda interschimbarii
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
    ///Metoda interschimbarii
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

int main()
{
    Citire(st, n);
    Afisare(st,n);
    OrdonareAlf(st, n);
    Afisare(st, n);
    OrdonareCredite(st, n);
    Afisare(st, n);
    OrdonareCredite1(st, n);
    Afisare(st, n);
    OrdonareAlfGrupa(st, n);
    Afisare(st, n);
   OrdonareInserDirecta(st, n, stb, m);
   Afisare(stb,m);
    return 0;
}
