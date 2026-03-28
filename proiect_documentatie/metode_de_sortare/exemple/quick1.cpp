#include <iostream>
#include <fstream>
using namespace std;
int n,v[10001];
int Imparte (int st,int dr)
{  int i,j,ii,jj,aux;
    i=st;
    j=dr;
    ii=0;
    jj=-1;
    while(i<j)
    {
        if(v[i]>v[j])
    {
        aux=v[i];
        v[i]=v[j];
        v[j]=aux;
        aux=ii;
        ii=-jj;
        jj=-aux;
    }
    i=i+ii;
    j=j+jj;
    }
    return i;
}
void Quick(int st, int dr)
{
    int p;
    if(st<dr)
    {
        p=Imparte(st,dr);
        Quick(st,p-1);
        Quick(p+1,dr);
    }
}
int main()
{
    int i;
    ifstream f("QUICK.IN");
    ofstream g("QUICK.OUT");
    f>>n;
    for(i=1;i<=n;i++)
        f>>v[i];
    Quick(1,n);
    for(i=1;i<=n;i++)
        g<<v[i]<<" ";
    return 0;
}
