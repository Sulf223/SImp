#include <iostream>
using namespace std;
int A[100],n;

void Pozitioneaza (int start, int finis,int &k)
{int i, j, d,aux;
d=0; i=start; j=finis;
while (i<j)
{if (A[i]>A[j])
{ aux=A[i];A[i]=A[j]; A[j]=aux; d=1-d ;
 }
   i+=d; j-=1-d;
}
k= i;
}

void Quick (int inceput, int sfarsit)
{ int k;
if (inceput < sfarsit)
{
Pozitioneaza (inceput, sfarsit, k);
Quick (inceput, k-1);
Quick (k+1, sfarsit);
}
}
int main()
{ int i;
cout<<"Quick - sort\n";
cout<<"Dati n = "; cin>>n;
for (i=0;i<n;i++)
{ cout<<" A["<< i<<"] = ";
cin>>A[i];
}
Quick(0, n-1);
cout<<"\nVectorul sortat este: ";
for (i=0;i<n;i++)cout<<A[ i]<<" ";
}


