#include <iostream>
#include <string.h>
///Pastram datele elevilor unei clase: nume, varsta si medie, sex.
///Citirea datelor celor n elevi b- aflati care esre este cel mai batran
///c- media generala a clasei -
///d-aflati daca exista in clasa un elev numit Popescu
///e- afisati fetele din clasa
using namespace std;
struct Elev{char nume[100];
             int v;
             float mg;
             char sex;
             } E[40];
struct Elev aux;
int main()
{ int n, i, maxe, p, j, ok;
float s;
    cout << "Nr de elevi!" ;
    cin >>n;cin.get();
    for(i=0;i<n;i++)
    {
        cin.get(E[i].nume, 100);
        cin>>E[i].v>>E[i].mg;
        cin.get();
        cin >>E[i].sex;
        cin.get();
    }
    cout <<"Lista clasei este ";
    for(i=0;i<n;i++)
        cout <<i+1<<" "<<E[i].nume<< " " <<E[i].v<<" "<<E[i].sex<<endl;
    maxe=0;
    for(i=0;i<n;i++)
        if(maxe <E[i].v)
         {
           maxe=E[i].v;
           p=i;
          }
    cout <<"Batranul "<<E[p].nume;
    s=0;
    for(i=0;i<n;i++)
     s =s  + E[i].mg;
    s= s/n;
    cout <<"Media generala a clasei " <<s;
    ok=0;
    for(i=0;i<n;i++)
        if(strcmp(E[i].nume,"Pop")==0)
           ok=1;
    if(ok==1)
        cout <<"Da avem ";

     cout <<"Lista fetelor este ";
    for(i=0;i<n;i++)
        if(E[i].sex=='F' )
         cout <<i+1<<" "<<E[i].nume<< " " <<E[i].v<<" "<<E[i].sex<<endl;
    for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
          if(strcmp (E[i].nume, E[j].nume)>0 )
          {
          aux= E[i];
          E[i]= E[j];
          E[j]=aux;
          }
     cout <<endl;
     cout <<"Lista alf este ";
    for(i=0;i<n;i++)
            cout <<i+1<<" "<<E[i].nume<< " " <<E[i].v<<" "<<E[i].sex<<endl;

    return 0;
}
