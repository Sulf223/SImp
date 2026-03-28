#include <iostream>
using namespace std;
int A[100],B[100],C[200], n, m;

int main()
{ int i,j,k;
cout<< "Dati nr. de elemente, apoi elementele: ";
cin>>n;
for(i=0;i<n;i++)
   cin>>A[i];

cout<< "Dati nr. de elemente, apoi elementele: ";
cin>>m;
for(i=0;i<m;i++)
   cin>>B[i];
k=0;i=0; j=0;
while (i<n && j<m)
 if (A[i] < B[j])
 {
  C[k]=A[i];
  i=i+1;
  k=k+1;
 }
else
{
  C[k]=B[j];
  j=j+1;
  k=k+1;
 }

if (i< n)
for (j=i;j<=n;j++)
{
 C[k]=A[j];
 k=k+1;
}
else
for ( i=j;i<=m;i++)
{
 C[k]=B[i];
 k=k+1;
}
cout <<"vect interclasat "<<endl;
for (i=0;i<n+m;i++)
     cout<<C[i]<<" ";
return 0;
}
