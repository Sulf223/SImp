#include <iostream>

using namespace std;

int main()
{
    int a[100], n, i, j, y, s, d, m;
    cout << "n = "; cin >> n;
    for(i = 0; i < n; i++)
    {
        cout << "a[" << i+1 << "] = ";
        cin >> y;
        s=0; d=i-1;
        while (s<=d)
        { m=(s+d)/2;
          if(y <a[m])
            d=m-1;
          else
            s= m+1;
        }
        for(j=i;j>=s+1;j--)
            a[j]=a[j-1];
        a[s]=y;
   }


    cout << endl;
    for(i = 0; i < n; i++)
        cout << "a[" << i+1 << "] = " << a[i] << endl;
    return 0;
}
