#include <iostream>

using namespace std;

int main()
{
    int a[100], n, i, j, y, s, d, m;
    cout << "n = "; cin >> n;
    int k=0;
    for(i = 0; i < n; i++)
    {
        cout << "a[" << i+1 << "] = ";
        cin >> y;
        s=0; d=k-1;
        while (s<=d)
        { m=(s+d)/2;
          if(y <a[m])
            d=m-1;
          else
            s= m+1;
        }

        if(a[s]!=y && a[d]!=y)
        {  k++;
              for(j=k;j>=s+1;j--)
            a[j]=a[j-1];
         a[s]=y;

        }
   }


    cout << endl;
    for(i = 0; i < k; i++)
        cout << "a[" << i+1 << "] = " << a[i] << endl;
    return 0;
}
