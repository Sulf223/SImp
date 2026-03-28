#include <iostream>
#include <string.h>
using namespace std;
struct produs{
        char denumire[50];
       float cantitate, pret;
       float valoare;
     };
struct produs p[800];

int n, m;
void Citire(struct produs p[], int &n)
{ int i;
    cin >>n;cin.get();
    for(i=0;i<n;i++)
    {   cout <<"Date date produs : den cant pret ";
        cin.get(p[i].denumire, 50); cin.get();

        cin>>p[i].cantitate>>p[i].pret;
        cin.get();
        p[i].valoare = p[i].cantitate *p[i].pret;
    }
}
void Afisare(struct produs p[], int n)
{ int i;
   cout <<"Lista de produse este :"<<endl;
   for(i=0;i<n;i++)
        cout <<i+1<<" : "<<p[i].denumire<<" "<<p[i].cantitate<<" "<<p[i].pret<<" "<<p[i].valoare<<endl;
}

void OrdonareAlf_Interschimbare(struct produs p[], int n)
{
    int i, j;
    ///Metoda interschimbarii
    struct produs aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++) /// x[i] >x[j]
          if(strcmp (p[i].denumire, p[j].denumire)>0 )
          {
          aux= p[i];
          p[i]= p[j];
          p[j]=aux;
          }
}


void OrdonareValoare(struct produs p[], int n)
{int i, j;
    ///Metoda interschimbarii
    struct produs aux;
     for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
          if(p[i].valoare < p[j].valoare || (p[i].valoare == p[j].valoare && strcmp(p[i].denumire, p[j].denumire)>0 ))
          {
          aux= p[i];
          p[i]= p[j];
          p[j]=aux;
          }
}


void CautareSecventiala (struct produs p[], int n, char den[20])
{  int i, poz;
    poz=-1;
  for(i=0;i<n;i++)
        if(strcmp(p[i].denumire, den)==0 )
            poz=i;
  if(poz>-1)
      cout <<i+1<<" : "<<p[i].denumire<<" "<<p[i].cantitate<<" "<<p[i].pret<<" "<<p[i].valoare<<endl;
  else
     cout <<"Nu exista";
  }

  void CautareBinara(struct produs p[], int n, char den[20])
{ /// Doar daca tabloul este ordonat
 int s, d, ok, m;
    s=0; d=n-1;
    ok=0;
    while (s <=d && ok==0)
    {
        m= (s+d)/2;
        /// Verific pe cel din mijloc
        if(strcmp(p[m].denumire, den)==0  )
            ok=1;
    }
    if(ok==1)
        cout <<m+1<<" : "<<p[m].denumire<<" "<<p[m].cantitate<<" "<<p[m].pret<<" "<<p[m].valoare<<endl;
  else
     cout <<"Nu exista";
}
int main()
{ char den[30];
    Citire(p, n);
    Afisare(p, n);
    OrdonareAlf_Interschimbare(p, n);
    cout <<endl;
    Afisare(p, n);

   OrdonareValoare(p, n);
   Afisare(p, n);


    cout<<"Dati produs de cautat ";
    cin >>den;

    CautareSecventiala(p, n, den);

    CautareBinara(p, n, den);

    return 0;
}
