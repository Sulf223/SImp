#include <iostream>

#include <fstream>
#include <algorithm>    // std::fill
#include <vector>       // std::vector
using namespace std;

vector <int> x;
int sec[] = {3,4,3}; //secventa de cautat
ifstream f("lulu.txt");
int t[100], n;
bool comp (int i,int j) { return (i<j); }

int main()
{   int el,i;i=0;
 //citire fisier
    while (f>>el)
    {
        x.push_back(el); t[i++]=el;
    }
n=x.size();
 //afisare ecran
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';
 cout <<endl;

int pozm= min_element(x.begin(),x.end(),comp)- x.begin();
cout <<"Minimul "<<x[pozm]<<endl;;

/* fill(x.begin(), x.end(), 20); //umplere cu o valoare fixa
 //afisare ecran
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';
*/
 sort(x.begin(), x.end(),comp ); // ordonare crescatoare
 //afisare ecran
 cout <<"vect sortat :";
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';
cout <<endl<<"rez cautare ";
if(find (x.begin(), x.end(), 4)!=x.end())
    cout <<"Da este ";
else
    cout <<"Nu este ";


//pt pozitia locului elementului cautat
int poz=find (x.begin(), x.end(), 2)-x.begin();
if (poz <x.size())
  cout <<"Elem este pe poz "<<poz;
else
    cout <<"Nu este elem ";
cout <<endl;
poz= search (x.begin(), x.end(), sec, sec+2)-x.begin();
if (poz <x.size())
  cout <<"poz "<<poz;
else
    cout <<"Nu este elem ";

//Generare permutari

do {

  cout <<endl<<"Permut vect  :";
  for(i =0; i<x.size() ;i++)
     cout << x[i] << ' ';

  } while ( next_permutation(x.begin(), x.end()) );

int k=3;// poz de inserat
x.insert ( x.begin() +k, 100);

cout <<endl<<"vect dupa inserare :";
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';

  // erase the 3th element
x.erase (x.begin()+2);
cout <<endl<<"vect dupa stergere :";
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';
//curatire vector
 x.clear();
 cout <<endl<<"vect dupa curatire:";
 for(i =0; i<x.size() ;i++)
    cout << x[i] << ' ';
 sort(t, t+n);
 for(i =0; i<n ;i++)
    cout << t[i] << ' ';
 cout <<endl;
    return 0;


}
