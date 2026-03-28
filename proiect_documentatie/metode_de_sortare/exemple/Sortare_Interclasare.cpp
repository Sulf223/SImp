#include <iostream>
using namespace std;
int A[100],n;
void Interclaseaza (int start, int mijloc, int finis)
{
int B[100], i, j, k;
k=start; i = start; j=mijloc+1;
while ( i<=mijloc && j<=finis)
if (A[i] < A[j])
{
B[k]=A[i];
i=i+1;
k=k+1;
}
else
{

B[k]=A[j];
j=j+1;
k=k+1;
}
if (i<= mijloc)
for (j=i;j<=mijloc;j++)
{
B[k]=A[j];
k=k+1;
}
else
for ( i=j;i<=finis;i++)
{
B[k]=A[i];
k=k+1;
}
for (i=start;i<=finis;i++)
A[i]= B[i];
}

void SortInterclas (int inceput,int sfarsit)
{ int centru;
if (inceput<sfarsit)
{
centru=(inceput + sfarsit) / 2;
SortInterclas (inceput, centru);
SortInterclas (centru+1, sfarsit);
Interclaseaza (inceput, centru, sfarsit);
}
}

int main()
{ int i;
cout<< "Dati nr. de elemente, apoi elementele: ";
cin>>n;
for(i=0;i<n;i++)
   cin>>A[i];
SortInterclas(0,n-1);
for(i=0;i<n;i++)
    cout<<A[i]<<" ";
return 0;
}
