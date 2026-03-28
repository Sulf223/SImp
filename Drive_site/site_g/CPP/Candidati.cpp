#include <iostream>
#include <fstream>
#include <string.h>
using namespace std;
struct candidat {
       char numec[100];
       float p1, p2, med;
       bool adm;
    };
struct candidat c[300];
ifstream f("candidati.txt");
ofstream g("admisi.txt");
int n;
void citire(struct candidat c[], int &n)
{ char nm[100]; float p1, p2;int i;
    i=0;
    while (f>>nm>>p1>>p2) /// while (!f.eof())
    {


      strcpy(c[i].numec, nm);
      c[i].p1= p1; c[i].p2=p2;
      c[i].med= (c[i].p1 +c[i].p2)/2;
      if(c[i].med>=7 && c[i].p1>=6 && c[i].p2>=6)
            c[i].adm=true;
      else
          c[i].adm=false;
      i++;
    }
    n=i;
}
void citire_ord(struct candidat c[], int &n)
{ char nm[100]; float p1, p2;int i, j;
   struct candidat y;
    n=0;
    while (f>>nm>>p1>>p2) /// while (!f.eof())
    { strcpy(y.numec, nm);
      y.p1= p1; y.p2=p2;
      y.med= (y.p1 +y.p2)/2;
      if(y.med>=7 && y.p1>=6 && y.p2>=6)
            y.adm=true;
      else
          y.adm=false;
        j=n-1;
        while ((j>=0) && (strcmp(c[j].numec,y.numec)>0))
        {
            c[j+1]=c[j];
            j--;
        }
       c[j+1]=y;
     n++;
     }
    }

void afisare(struct candidat c[], int n)
{
    cout <<"Lista candidatilor "<<endl;
    for(int i=0;i<n;i++)
         cout <<c[i].numec<<" "<<c[i].p1<<" "<<c[i].p2<<" "<<c[i].med<<endl;
}
int main()
{
    citire_ord(c, n);
    afisare(c, n);
    return 0;
}
