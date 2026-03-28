#include <iostream>

using namespace std;
int x[1000],y[1000],z[1000],n;
int main()
{ int i,j;
    cout << "Dati n " ;
    cin >>n;
    for(i=0;i<n;i++)
        cin >>x[i];
    //Metoda interschimbare

      for(i=0;i<n-1;i++)
       for(j=i+1;j<n;j++)
         if( x[i] > x[j])
              y[i]++;
            else
               y[j]++;
       for(i=0;i<n;i++)
        z[y[i]] = x[i];
   cout <<"Vect ordonat este ";
   for(i=0;i<n;i++)
        cout <<z[i]<<" ";
    return 0;
}

